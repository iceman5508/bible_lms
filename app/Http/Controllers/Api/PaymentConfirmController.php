<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Logger;
use App\Http\Services\Payment\BasePaymentService;
use App\Models\Addon\Product\Product;
use App\Models\CartManagement;
use App\Models\Order;
use App\Models\Package;
use App\Models\Payment;
use App\Models\UserPackage;
use App\Models\WalletRecharge;
use App\Traits\ApiStatusTrait;
use App\Traits\ImageSaveTrait;
use App\Traits\SendNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentConfirmController extends Controller
{
    use ImageSaveTrait, ApiStatusTrait, SendNotification;
    protected $logger;

    public function __construct()
    {
        $this->logger = new Logger();
    }

    public function paymentOrderNotifier(Request $request, $id)
    {
        $payment_id = $request->input('paymentId', '-1');
        $payer_id = $request->input('PayerID', '-1');
        $im_payment_id = $request->input('payment_id', '-1');
        $this->logger->log('Payment Start', '==========');
        $this->logger->log('Payment paymentId', $payment_id);
        $this->logger->log('Payment PayerID', $payer_id);
        $order = Order::where(['uuid' => $id, 'payment_status' => ORDER_PAYMENT_STATUS_DUE])->first();
        if (is_null($order)) {
            $returnData['status'] = false;
            $returnData['message'] = __('No pending payment found against this order. Please close this window');
            return view('payment-api-success', $returnData);
        }
        Log::info($order);

        if ($order->payment_method == MERCADOPAGO) {
            $order->payment_id = $im_payment_id;
            $order->save();
        }

        $this->logger->log('Payment verify request : ', json_encode($request->all()));

        $payment_id = $order->payment_id;
        $data = ['id' => $order->uuid, 'payment_method' => getPaymentMethodId($order->payment_method), 'currency' => $order->payment_currency];
        $getWay = new BasePaymentService($data);
        if ($payer_id != '-1') {
            $payment_data = $getWay->paymentConfirmation($payment_id, $payer_id);
        } else if ($order->payment_method == IYZIPAY) {
            $payment_data = $getWay->paymentConfirmation($payment_id, $request->token);
        } else {
            $payment_data = $getWay->paymentConfirmation($payment_id);
        }

        $this->logger->log('Payment done for order', json_encode($order));
        $this->logger->log('Payment details', json_encode($payment_data));

        if ($payment_data['success']) {
            if ($payment_data['data']['payment_status'] == 'success') {
                CartManagement::whereUserId($order->user_id)->delete();
                DB::beginTransaction();
                try {
                    $order->payment_status = 'paid';
                    $order->payment_method = $payment_data['data']['payment_method'];
                    $order->save();
                    $this->logger->log('status', 'paid');

                    /* Start:: Create transaction, affiliate history, user balance increment/decrement */
                    distributeCommission($order);
                    /* End:: Create transaction, affiliate history, user balance increment/decrement */

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->logger->log('End with Exception', $e->getMessage());
                }
                $text = __("New student enrolled");
                $target_url = route('instructor.all-student');
                foreach ($order->items as $item) {
                    if ($item->course) {
                        $this->sendForApi($text, 2, $target_url, $item->course->user_id, $order->user_id);
                    } elseif (!is_null($item->product_id)) {
                        Product::where('id', $item->product_id)->decrement('quantity', $item->unit);

                        $text = __("Your have purchase product.");
                        $target_url = route('lms_product.student.purchase_list');

                        /** ====== Send notification to instructor =========*/
                        $text2 = "New product sold";
                        $target_url2 = route('lms_product.instructor.product.my-product');
                        $this->sendForApi($text2, 2, $target_url2, @$item->product->user_id, $order->user_id);
                        /** ====== Send notification to instructor =========*/
                    } else {
                        $text = __("Your bank payment has been cancelled.");
                        $target_url = route('lms_product.student.purchase_list');
                        $this->sendForApi($text, 3, $target_url, $order->user_id,  $order->user_id);
                    }
                }

                $text = __("Item has been sold");
                $this->sendForApi($text, 1, null, null,  $order->user_id, null);

                $returnData['status'] = true;
                $returnData['message'] = __('Payment has been successfully. Please close this window');
                return view('payment-api-success', $returnData);
            }
        } else {
            $returnData['status'] = false;
            $returnData['message'] = __('Payment has been failed. Please close this window');
            return view('payment-api-success', $returnData);
        }

        $returnData['status'] = false;
        $returnData['message'] = __('Payment has been failed. Please close this window');
        return view('payment-api-success', $returnData);
    }

    public function paymentCancel(Request $request)
    {
        return $this->failed([], __('Payment has been cancelled'));
    }
}
