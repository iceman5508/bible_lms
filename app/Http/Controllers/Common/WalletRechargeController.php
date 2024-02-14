<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Http\Controllers\Logger;
use App\Http\Services\Payment\BasePaymentService;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use App\Traits\SendNotification;
use Illuminate\Http\Request;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use App\Models\Payment as ModelPayment;
use App\Models\WalletRecharge;
use Razorpay\Api\Api;
use Exception;
use Illuminate\Support\Facades\DB;
use Session;
use Redirect;
use Mollie\Laravel\Facades\Mollie;

class WalletRechargeController extends Controller
{

    use ImageSaveTrait, General, SendNotification;

    private $_api_context;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $logger;

    public function __construct()
    {
        /** Mollie api key **/
        if (env('MOLLIE_KEY')) {
            Mollie::api()->setApiKey(env('MOLLIE_KEY'));
        }

        /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(
            new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret']
            )
        );
        $this->_api_context->setConfig($paypal_conf['settings']);

        $this->logger = new Logger();
    }

    public function checkout(Request $request)
    {
        if(!get_option('wallet_recharge_system', 0)){
            $this->showToastrMessage('error', 'Wallet recharge is not enable');
            return redirect()->back();
        }

        $data['pageTitle'] = __('Recharge Wallet');
        $data['navPaymentActiveClass'] = 'active';
        $data['banks'] = Bank::orderBy('name', 'asc')->where('status', 1)->get();
        $amount = $request->amount;
        $data['amount'] = $amount;
        if($amount > 0){
            $razorpay_grand_total_with_conversion_rate = ($amount + get_platform_charge($amount)) * (get_option('razorpay_conversion_rate') ? get_option('razorpay_conversion_rate') : 0);
            $data['razorpay_grand_total_with_conversion_rate'] = (float)preg_replace("/[^0-9.]+/", "", number_format($razorpay_grand_total_with_conversion_rate, 2));

            $paystack_grand_total_with_conversion_rate = ($amount + get_platform_charge($amount)) * (get_option('paystack_conversion_rate') ? get_option('paystack_conversion_rate') : 0);
            $data['paystack_grand_total_with_conversion_rate'] = (float)preg_replace("/[^0-9.]+/", "", number_format($paystack_grand_total_with_conversion_rate, 2));

            $sslcommerz_grand_total_with_conversion_rate = ($amount + get_platform_charge($amount)) * (get_option('sslcommerz_conversion_rate') ? get_option('sslcommerz_conversion_rate') : 0);
            $data['sslcommerz_grand_total_with_conversion_rate'] = (float)preg_replace("/[^0-9.]+/", "", number_format($sslcommerz_grand_total_with_conversion_rate, 2));
            return view('frontend.wallet.recharge', $data);
        }
        else{
            $this->showToastrMessage('error', 'Amount must be grater than 0');
            return redirect()->back();
        }
    }

    public function razorPayPayment(Request $request)
    {
        $input = $request->all();
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        if (empty(env('RAZORPAY_KEY')) && empty(env('RAZORPAY_SECRET'))) {
            $this->showToastrMessage('error', __('Razorpay payment gateway off!'));
            return redirect()->back();
        }
        DB::beginTransaction();
        try {
            $payment = $api->payment->fetch($input['razorpay_payment_id']);
            $this->logger->log('transaction razorpay ', json_encode($payment));

            if (count($input) && !empty($input['razorpay_payment_id'])) {
                try {
                    $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));
                } catch (Exception $e) {
                    DB::rollBack();
                    Session::put('error', $e->getMessage());
                    return redirect()->back();
                }
            }
            $payment = $this->placeOrder($request->payment_method, $request);

            if(!$payment){
                DB::rollBack();
                $this->showToastrMessage('error', __('Something went wrong!'));
                return redirect()->back();
            }

            $payment->payment_status = 'paid';
            $payment->payment_method = 'razorpay';

            $payment_currency = get_option('razorpay_currency');
            $conversion_rate = get_option('razorpay_conversion_rate') ? get_option('razorpay_conversion_rate') : 0;

            $payment->payment_currency = $payment_currency;
            $payment->conversion_rate = $conversion_rate;
            $payment->grand_total_with_conversation_rate = ($payment->sub_total + $payment->platform_charge) * $conversion_rate;
            $payment->save();

            //add to wallet_recharge
            $walletRecharge = WalletRecharge::create([
                'payment_id' => $payment->id,
                'amount' => $payment->sub_total,
                'payment_method' => $payment->payment_method,
                'type' => PAYMENT_TYPE_WALLET_RECHARGE
            ]);

            createTransaction(auth()->id(), $payment->sub_total, TRANSACTION_WALLET_RECHARGE, 'Wallet Recharge', 'Recharge (' . $walletRecharge->id . ')', $walletRecharge->id);
            $payment->user->increment('balance', decimal_to_int($payment->sub_total));

            /** ====== Send notification =========*/
            $text = __("Wallet recharge completed");
            $this->send($text, 3, null , auth()->id());

            $text = __("Wallet recharge");
            $this->send($text, 1, null, null);
            /** ====== Send notification =========*/
            $this->showToastrMessage('success', 'Payment has been completed');

            DB::commit();

            return redirect()->route('wallet_recharge.thank-you');

        } catch (Exception $exception) {
            DB::rollBack();
            $this->logger->log('transaction failed razorpay', $exception->getMessage());
            $this->showToastrMessage('error', __('Something went wrong!'));
            return redirect()->back();
        }
    }

    public function pay(Request $request)
    {
        if (is_null($request->payment_method)) {
            $this->showToastrMessage('warning', 'Please Select Payment Method');
            return redirect()->back();
        }

        if ($request->payment_method == BANK) {
            if (empty($request->deposit_by) || is_null($request->deposit_slip)) {
                $this->showToastrMessage('error', 'Bank Information Not Valid!');
                return redirect()->back();
            }
        }

        if ($request->payment_method == PAYPAL) {
            if (empty(env('PAYPAL_CLIENT_ID')) || empty(env('PAYPAL_SECRET')) || empty(env('PAYPAL_MODE'))) {
                $this->showToastrMessage('error', 'Paypal payment gateway is off!');
                return redirect()->back();
            }

            $conversion_rate = (get_option('paypal_conversion_rate') ? get_option('paypal_conversion_rate') : 0);
            $currency = get_option('paypal_currency');
        }

        if ($request->payment_method == MOLLIE) {
            if (empty(env('MOLLIE_KEY'))) {
                $this->showToastrMessage('error', 'Mollie payment gateway is off!');
                return redirect()->back();
            }

            $conversion_rate = (get_option('mollie_conversion_rate') ? get_option('mollie_conversion_rate') : 0);
            $currency = get_option('mollie_currency');
        }

        if ($request->payment_method == INSTAMOJO) {
            if (empty(env('IM_API_KEY')) || empty(env('IM_AUTH_TOKEN')) || empty(env('IM_URL'))) {
                $this->showToastrMessage('error', 'Instamojo payment gateway is off!');
                return redirect()->back();
            }

            $conversion_rate = (get_option('im_conversion_rate') ? get_option('im_conversion_rate') : 0);
            $currency = get_option('im_currency');
        }

        if ($request->payment_method == PAYSTAC) {
            if (empty(env('PAYSTACK_PUBLIC_KEY')) || empty(env('PAYSTACK_SECRET_KEY'))) {
                $this->showToastrMessage('error', 'Paystack payment gateway is off!');
                return redirect()->back();
            }

            $conversion_rate = (get_option('paystack_conversion_rate') ? get_option('paystack_conversion_rate') : 0);
            $currency = get_option('paystack_currency');
        }
        if ($request->payment_method == MERCADOPAGO) {
            if (empty(env('MERCADO_PAGO_CLIENT_ID')) || empty(env('MERCADO_PAGO_CLIENT_SECRET'))) {
                $this->showToastrMessage('error', 'MERCADO_PAGO payment gateway is off!');
                return redirect()->back();
            }

            $conversion_rate = (get_option('mercado_conversion_rate') ? get_option('mercado_conversion_rate') : 0);
            $currency = get_option('mercado_currency');
        }
        if ($request->payment_method == FLUTTERWAVE) {
            if (empty(env('FLW_PUBLIC_KEY')) || empty(env('FLW_SECRET_KEY'))) {
                $this->showToastrMessage('error', 'Flutterwave payment gateway is off!');
                return redirect()->back();
            }

            $conversion_rate = (get_option('flutterwave_conversion_rate') ? get_option('flutterwave_conversion_rate') : 0);
            $currency = get_option('flutterwave_currency');
        }
        if ($request->payment_method == COINBASE) {
            if (empty(get_option('coinbase_key'))) {
                $this->showToastrMessage('error', 'Coinbase payment gateway is off!');
                return redirect()->back();
            }

            $conversion_rate = (get_option('coinbase_conversion_rate') ? get_option('coinbase_conversion_rate') : 0);
            $currency = get_option('coinbase_currency');
        }

        if ($request->payment_method == ZITOPAY) {
            if (empty(get_option('zitopay_username'))) {
                $this->showToastrMessage('error', 'Zitopay payment gateway is off!');
                return redirect()->back();
            }

            $conversion_rate = (get_option('zitopay_conversion_rate') ? get_option('zitopay_conversion_rate') : 0);
            $currency = get_option('zitopay_currency');
        }

        if ($request->payment_method == IYZIPAY) {
            if (empty(get_option('iyzipay_key'))) {
                $this->showToastrMessage('error', 'Iyzipay payment gateway is off!');
                return redirect()->back();
            }

            $conversion_rate = (get_option('iyzipay_conversion_rate') ? get_option('iyzipay_conversion_rate') : 0);
            $currency = get_option('iyzipay_currency');
        }

        if ($request->payment_method == BITPAY) {
            if (empty(get_option('bitpay_key'))) {
                $this->showToastrMessage('error', 'Bitpay payment gateway is off!');
                return redirect()->back();
            }

            $conversion_rate = (get_option('bitpay_conversion_rate') ? get_option('bitpay_conversion_rate') : 0);
            $currency = get_option('bitpay_currency');
        }
        
        if ($request->payment_method == BRAINTREE) {
            if (empty(get_option('braintree_key'))) {
                $this->showToastrMessage('error', 'Braintree payment gateway is off!');
                return redirect()->back();
            }

            $conversion_rate = (get_option('braintree_conversion_rate') ? get_option('braintree_conversion_rate') : 0);
            $currency = get_option('braintree_currency');
        }

        $payment = $this->placeOrder($request->payment_method, $request);

        if ($request->payment_method == BANK) {
            $deposit_by = $request->deposit_by;
            $deposit_slip = $this->uploadFileWithDetails('bank', $request->deposit_slip);
            if (!$deposit_slip['is_uploaded']) {
                $this->showToastrMessage('error', 'Something went wrong! Failed to upload file');
                return redirect()->back();
            }

            $payment->payment_status = 'pending';
            $payment->payment_id = $payment->uuid;
            $payment->deposit_by = $deposit_by;
            $payment->deposit_slip = $deposit_slip['path'];
            $payment->payment_method = 'bank';
            $payment->bank_id = $request->bank_id;
            $payment->save();

           
            //add to wallet_recharge
            WalletRecharge::create([
                'payment_id' => $payment->id,
                'amount' => $payment->sub_total,
                'payment_method' => $payment->payment_method,
                'type' => PAYMENT_TYPE_WALLET_RECHARGE,
                'status' => STATUS_PENDING
            ]);

            /** ====== Send notification =========*/
            $text = __("Wallet Recharge request");
            $this->send($text, 1, null, null);
            /** ====== Send notification =========*/
            $this->showToastrMessage('success', 'Request has been Placed! Please Wait for Approve');
            return redirect()->route('wallet_recharge.thank-you');
        } else if ($request->payment_method == SSLCOMMERZ)  {

            $total = $payment->grand_total * (get_option('sslcommerz_conversion_rate') ? get_option('sslcommerz_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            $user = auth()->user();
            # CUSTOMER INFORMATION
            $post_data = array();
            $post_data['tran_id'] = $payment->uuid; // tran_id must be unique
            $post_data['product_category'] = "Payment for purchase";

            $post_data['cus_name'] = $user->name;
            $post_data['cus_phone'] = $user->phone_number ?? '011111111';
            $post_data['cus_email'] = $user->email;
            $post_data['cus_add1'] = $user->address;
            $post_data['cus_add2'] = "";
            $post_data['cus_city'] = "";
            $post_data['cus_state'] = "";
            $post_data['cus_postcode'] = @$user->student->country->postal_code ?? '017';
            $post_data['cus_country'] = @$user->student->country->country_name ?? 'BD';
            $post_data['cus_fax'] = "";

            # SHIPMENT INFORMATION
            $post_data['ship_name'] = get_option('app_name') ?? 'LMS Store';
            $post_data['ship_add1'] = $request->input('phone_number',$user->address);
            $post_data['ship_add2'] =  '';
            $post_data['ship_city'] =  '';
            $post_data['ship_state'] =  '';
            $post_data['ship_postcode'] = '';
            $post_data['ship_phone'] = $request->input('phone_number',$user->address);
            $post_data['ship_country'] = @$user->student->country->country_name ?? 'BD';

            $post_data['shipping_method'] = "NO";
            $post_data['product_name'] = "Wallet Recharge";

            $post_data['product_profile'] = "digital-goods";

            # OPTIONAL PARAMETERS
            $post_data['value_a'] = "ref001";
            $post_data['value_b'] = "ref002";
            $post_data['value_c'] = "ref003";
            $post_data['value_d'] = "ref004";

            $object = [
                'id' => $payment->uuid,
                'payment_method' => SSLCOMMERZ,
                'currency' => get_option('sslcommerz_currency'),
                'successUrl' => route('paymentNotify.wallet_recharge', $payment->uuid)
            ];

            $getWay = new BasePaymentService($object);
            $responseData = $getWay->makePayment($total,$post_data);
            if($responseData['success']){
                $payment->payment_id = $responseData['payment_id'];
                $payment->save();
                return Redirect::away($responseData['redirect_url']);
            }else{
                $this->showToastrMessage('error', 'Something went wrong!');
                return redirect()->back();
            }
        } else if ($request->payment_method == STRIPE)  {

            $total = $payment->grand_total * (get_option('stripe_conversion_rate') ? get_option('stripe_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');

            $object = [
                'id' => $payment->uuid,
                'payment_method' => STRIPE,
                'currency' => get_option('stripe_currency'),
                'token' => $request->stripeToken
            ];
            $getWay = new BasePaymentService($object);
            $responseData = $getWay->makePayment($total);

            if($responseData['success']){
                if($responseData['data']['payment_status'] == 'success') {
                    $payment->payment_id = $responseData['payment_id'];
                    $payment->payment_status = 'paid';
                    $payment->save();

                    //add to wallet_recharge
                    $walletRecharge = WalletRecharge::create([
                        'payment_id' => $payment->id,
                        'amount' => $payment->sub_total,
                        'payment_method' => $payment->payment_method,
                        'type' => PAYMENT_TYPE_WALLET_RECHARGE
                    ]);

                    createTransaction(auth()->id(), $payment->sub_total, TRANSACTION_WALLET_RECHARGE, 'Wallet Recharge', 'Recharge (' . $walletRecharge->id . ')', $walletRecharge->id);
                    $payment->user->increment('balance', decimal_to_int($payment->sub_total));

                    /** ====== Send notification =========*/
                    $text = __("Wallet recharge completed");
                    $this->send($text, 3, null , auth()->id());

                    $text = __("Wallet Recharge");
                    $this->send($text, 1, null, null);
                    /** ====== Send notification =========*/
                    $this->showToastrMessage('success', 'Payment has been completed');
                    return redirect()->route('wallet_recharge.thank-you');
                }
            }
            $this->showToastrMessage('error', 'Something went wrong!');
            return redirect()->back();
        }else{
            $total = $payment->grand_total * $conversion_rate;
            $total = number_format($total, 2,'.','');
            $object = [
                'id' => $payment->uuid,
                'payment_method' => $request->payment_method,
                'currency' => $currency,
                'successUrl' => route('paymentNotify.wallet_recharge', $payment->uuid)
            ];

            $getWay = new BasePaymentService($object);
            $responseData = $getWay->makePayment($total);
            if($responseData['success']){
                $payment->payment_id = $responseData['payment_id'];
                $payment->save();
                return Redirect::away($responseData['redirect_url']);
            }else{
                $this->showToastrMessage('error', $responseData['message']);
                return redirect()->back();
            }
        }
    }

    private function placeOrder($payment_method, $request)
    {
        $price = $request->amount;
        $data = [];
        $data['user_id'] = auth()->user()->id;
        $data['order_number'] = rand(100000, 999999);
        $data['sub_total'] = $price;
        $data['platform_charge'] = get_platform_charge($price);
        $data['current_currency'] = get_currency_code();
        $data['grand_total'] = $data['sub_total'] + $data['platform_charge'];
        $data['payment_method'] = $payment_method;

        $payment_currency = '';
        $conversion_rate = '';

        if ($payment_method == PAYPAL) {
            $payment_currency = get_option('paypal_currency');
            $conversion_rate = get_option('paypal_conversion_rate') ? get_option('paypal_conversion_rate') : 0;
        } elseif ($payment_method == STRIPE) {
            $payment_currency = get_option('stripe_currency');
            $conversion_rate = get_option('stripe_conversion_rate') ? get_option('stripe_conversion_rate') : 0;
        } elseif ($payment_method == BANK) {
            $payment_currency = get_option('bank_currency');
            $conversion_rate = get_option('bank_conversion_rate') ? get_option('bank_conversion_rate') : 0;
        } elseif ($payment_method == MOLLIE) {
            $payment_currency = get_option('mollie_currency');
            $conversion_rate = get_option('mollie_conversion_rate') ? get_option('mollie_conversion_rate') : 0;
        } elseif ($payment_method == FLUTTERWAVE) {
            $payment_currency = get_option('flutterwave_currency');
            $conversion_rate = get_option('flutterwave_conversion_rate') ? get_option('flutterwave_conversion_rate') : 0;
        } elseif ($payment_method == MERCADOPAGO) {
            $payment_currency = get_option('mercado_currency');
            $conversion_rate = get_option('mercado_conversion_rate') ? get_option('mercado_conversion_rate') : 0;
        } elseif ($payment_method == INSTAMOJO) {
            $payment_currency = get_option('im_currency');
            $conversion_rate = get_option('im_conversion_rate') ? get_option('im_conversion_rate') : 0;
        } elseif ($payment_method == PAYSTAC) {
            $payment_currency = get_option('paystack_currency');
            $conversion_rate = get_option('paystack_conversion_rate') ? get_option('paystack_conversion_rate') : 0;
        } elseif ($payment_method == COINBASE) {
            $payment_currency = get_option('coinbase_currency');
            $conversion_rate = get_option('coinbase_conversion_rate') ? get_option('coinbase_conversion_rate') : 0;
        } elseif ($payment_method == ZITOPAY) {
            $payment_currency = get_option('zitopay_currency');
            $conversion_rate = get_option('zitopay_conversion_rate') ? get_option('zitopay_conversion_rate') : 0;
        } elseif ($payment_method == IYZIPAY) {
            $payment_currency = get_option('iyzipay_currency');
            $conversion_rate = get_option('iyzipay_conversion_rate') ? get_option('iyzipay_conversion_rate') : 0;
        } elseif ($payment_method == BITPAY) {
            $payment_currency = get_option('bitpay_currency');
            $conversion_rate = get_option('bitpay_conversion_rate') ? get_option('bitpay_conversion_rate') : 0;
        } elseif ($payment_method == BRAINTREE) {
            $payment_currency = get_option('braintree_currency');
            $conversion_rate = get_option('braintree_conversion_rate') ? get_option('braintree_conversion_rate') : 0;
        }

        $data['payment_currency'] = $payment_currency;
        $data['conversion_rate'] = $conversion_rate;
        if ($conversion_rate) {
            $data['grand_total_with_conversation_rate'] = ($data['sub_total'] + $data['platform_charge']) * $conversion_rate;
        }

        $data['payment_details'] = json_encode([
            'user_id' => auth()->id(),
            'amount' => $price,
            'user' => auth()->user(),
        ]);

        $payment = ModelPayment::create($data);

        return $payment;
    }

    public function thankYou()
    {
        $data['pageTitle'] = __('Wallet Recharge');

        return view('frontend.thankyou_wallet_recharge', $data);
    }
}
