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
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use App\Traits\SendNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentApiController extends Controller
{
    use ImageSaveTrait, General, SendNotification;
    protected $logger;

    public function __construct()
    {
        $this->logger = new Logger();
    }
    public function paymentNotifier(Request $request, $id)
    {
        $payment_id = $request->input('paymentId', '-1');
        $payer_id = $request->input('PayerID', '-1');
        $im_payment_id = $request->input('payment_id', '-1');
        $this->logger->log('Payment Start', '==========');
        $this->logger->log('Payment paymentId', $payment_id);
        $this->logger->log('Payment PayerID', $payer_id);
        $order = Order::where(['uuid' => $id, 'payment_status' => ORDER_PAYMENT_STATUS_DUE])->first();
        if (is_null($order)) {
            $this->showToastrMessage('error', __(SWR));
            return redirect()->route('student.cartList');
        }
        Log::info($order);

        if($order->payment_method == MERCADOPAGO){
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
                CartManagement::whereUserId(auth()->id())->delete();
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
                        $this->send($text, 2, $target_url, $item->course->user_id);
                    }
                    elseif(!is_null($item->product_id)){
                        Product::where('id', $item->product_id)->decrement('quantity', $item->unit);

                        $text = __("Your have purchase product.");
                        $target_url = route('lms_product.student.purchase_list');
        
                        /** ====== Send notification to instructor =========*/
                        $text2 = "New product sold";
                        $target_url2 = route('lms_product.instructor.product.my-product');
                        $this->send($text2, 2, $target_url2, @$item->product->user_id);
                        /** ====== Send notification to instructor =========*/
        
                    } else {
                        $text = __("Your bank payment has been cancelled.");
                        $target_url = route('lms_product.student.purchase_list');
                        $this->send($text, 3, $target_url, $order->user_id);
                    }
            
                }

                $text = __("Item has been sold");
                $this->send($text, 1, null, null);
                $this->showToastrMessage('success', __('Payment has been completed'));
                return redirect()->route('student.thank-you');
            }
        }

        $this->showToastrMessage('error', __('Payment has been declined'));
        return redirect()->route('main.index');
    }

    public function paymentSubscriptionNotifier(Request $request, $id)
    {
        $payment_id = $request->input('paymentId', '-1');
        $payer_id = $request->input('PayerID', '-1');
        $im_payment_id = $request->input('payment_id', '-1');
        $this->logger->log('Payment Start', '==========');
        $this->logger->log('Payment paymentId', $payment_id);
        $this->logger->log('Payment PayerID', $payer_id);
        $order = Payment::where(['uuid' => $id, 'payment_status' => ORDER_PAYMENT_STATUS_DUE])->first();
        if (is_null($order)) {
            $this->showToastrMessage('error', SWR);
            return redirect()->route('main.index');
        }
        $payment_id = $order->payment_id;
        $data = ['id' => $order->uuid, 'payment_method' => getPaymentMethodId($order->payment_method), 'currency' => $order->payment_currency];
        $getWay = new BasePaymentService($data);
        if ($payer_id != '-1') {
            $payment_data = $getWay->paymentConfirmation($payment_id, $payer_id);
        } else {
            $payment_data = $getWay->paymentConfirmation($payment_id);
        }

        $this->logger->log('s-Payment done for order', json_encode($order));
        $this->logger->log('s-Payment details', json_encode($payment_data));

        if ($payment_data['success']) {
            if ($payment_data['data']['payment_status'] == 'success') {
                DB::beginTransaction();
                try {
                    $order->payment_status = 'paid';
                    $order->payment_method = $payment_data['data']['payment_method'];
                    $order->save();

                    //add to user package from here
                    $months = ($request->subscription_type) ? 1 : 12;
                    $userPackageData = json_decode($order->payment_details, true);
                    $userPackageData['payment_id'] = $order->id;
                    $userPackageData['enroll_date'] = now();
                    $userPackageData['expired_date'] = Carbon::now()->addMonths($months);
                    $package = Package::where('id', $userPackageData['package_id'])->first();
                    UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->where('package_type', $package->package_type)->where('user_packages.user_id', auth()->id())->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->update(['user_packages.status' => PACKAGE_STATUS_CANCELED]);
                    UserPackage::create($userPackageData);

                    $this->logger->log('status', 'paid');

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->logger->log('End with Exception', $e->getMessage());
                }

                /** ====== Send notification =========*/
                $text = __("Subscription purchase completed");
                $this->send($text, 3, null , auth()->id());

                $text = __("Subscription has been sold");
                $package_id = json_decode($order->payment_details, true)['package_id'];
                $package = Package::whereId($package_id)->first();
                $target_url = ($package->package_type == PACKAGE_TYPE_SUBSCRIPTION) ? route('admin.subscriptions.purchase_pending_list') : route('admin.saas.purchase_pending_list');
                $this->send($text, 1, $target_url, null);
                /** ====== Send notification =========*/

                $this->showToastrMessage('success', __('Payment has been completed'));
                return redirect()->route('subscription.thank-you');
            }
        }

        $this->showToastrMessage('error', __('Payment has been declined'));
        return redirect()->route('main.index');
    }
   
    public function paymentWalletRechargeNotifier(Request $request, $id)
    {
        $payment_id = $request->input('paymentId', '-1');
        $payer_id = $request->input('PayerID', '-1');
        $im_payment_id = $request->input('payment_id', '-1');
        $this->logger->log('Payment Start', '==========');
        $this->logger->log('Payment paymentId', $payment_id);
        $this->logger->log('Payment PayerID', $payer_id);
        $order = Payment::where(['uuid' => $id, 'payment_status' => ORDER_PAYMENT_STATUS_DUE])->first();
        if (is_null($order)) {
            $this->showToastrMessage('error', SWR);
            return redirect()->route('main.index');
        }
        $payment_id = $order->payment_id;
        $data = ['id' => $order->uuid, 'payment_method' => getPaymentMethodId($order->payment_method), 'currency' => $order->payment_currency];
        $getWay = new BasePaymentService($data);
        if ($payer_id != '-1') {
            $payment_data = $getWay->paymentConfirmation($payment_id, $payer_id);
        } else {
            $payment_data = $getWay->paymentConfirmation($payment_id);
        }

        $this->logger->log('s-Payment done for order', json_encode($order));
        $this->logger->log('s-Payment details', json_encode($payment_data));

        if ($payment_data['success']) {
            if ($payment_data['data']['payment_status'] == 'success') {
                DB::beginTransaction();
                try {
                    $order->payment_status = 'paid';
                    $order->payment_method = $payment_data['data']['payment_method'];
                    $order->save();

                    //add to wallet_recharge
                    $walletRecharge = WalletRecharge::create([
                        'payment_id' => $order->id,
                        'amount' => $order->sub_total,
                        'payment_method' => $order->payment_method,
                        'type' => PAYMENT_TYPE_WALLET_RECHARGE
                    ]);

                    createTransaction(auth()->id(), $order->sub_total, TRANSACTION_WALLET_RECHARGE, 'Wallet Recharge', 'Recharge (' . $walletRecharge->id . ')', $walletRecharge->id);
                    $order->user->increment('balance', decimal_to_int($order->sub_total));

                    $this->logger->log('status', 'paid');

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->logger->log('End with Exception', $e->getMessage());
                }

                /** ====== Send notification =========*/
                $text = __("Wallet recharge completed");
                $this->send($text, 3, null , auth()->id());

                $text = __("Wallet recharge");
                $this->send($text, 1, null, null);
                /** ====== Send notification =========*/

                $this->showToastrMessage('success', __('Payment has been completed'));
                return redirect()->route('wallet_recharge.thank-you');
            }
        }

        $this->showToastrMessage('error', __('Payment has been declined'));
        return redirect()->route('main.index');
    }

    public function paymentCancel(Request $request){
        $this->showToastrMessage('error', __('Payment has been canceled'));
        return redirect()->route('main.index');
    }
}
