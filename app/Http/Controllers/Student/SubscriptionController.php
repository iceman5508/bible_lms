<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Logger;
use App\Http\Services\Payment\BasePaymentService;
use App\Models\Bank;
use App\Models\City;
use App\Models\Country;
use App\Models\Enrollment;
use App\Models\Package;
use App\Models\State;
use App\Models\Student;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use App\Traits\SendNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use App\Models\Payment as ModelPayment;
use App\Models\UserPackage;
use Razorpay\Api\Api;
use Exception;
use Illuminate\Support\Facades\DB;
use Session;
use Carbon\Carbon;
use Redirect;
use Mollie\Laravel\Facades\Mollie;

class SubscriptionController extends Controller
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

    public function checkout(Request $request, Package $subscription)
    {
        if($subscription->package_type == PACKAGE_TYPE_SAAS_INSTRUCTOR && auth()->user()->role != USER_ROLE_INSTRUCTOR){
            $this->showToastrMessage('warning', 'You are not an instructor');
            return back();
        }
        elseif($subscription->package_type == PACKAGE_TYPE_SAAS_ORGANIZATION && auth()->user()->role != USER_ROLE_ORGANIZATION){
            $this->showToastrMessage('warning', 'You are not an organization');
            return back();
        }
        
        $data['pageTitle'] = ($subscription->package_type == PACKAGE_TYPE_SUBSCRIPTION) ? __("Subscription Checkout") : __("SaaS Package Checkout");
        $data['price'] = ($request->monthly == 1) ? $subscription->discounted_monthly_price : $subscription->discounted_yearly_price;
        $data['subscription_type'] = ($request->monthly == 1) ? __('Monthly') : __('Yearly');
        $data['monthly'] = $request->monthly;
        $data['package'] = $subscription;
        if($data['price'] < 1){
            DB::beginTransaction();
            try{
                $payment_details = [
                    'user_id' => auth()->id(),
                    'package_id' => $subscription->id,
                    'subscription_type' => ($request->monthly) ? SUBSCRIPTION_TYPE_MONTHLY : SUBSCRIPTION_TYPE_YEARLY,
                    'student' => $subscription->student,
                    'instructor' => $subscription->instructor,
                    'course' => $subscription->course,
                    'consultancy' => $subscription->consultancy,
                    'subscription_course' => $subscription->subscription_course,
                    'bundle_course' => $subscription->bundle_course,
                    'product' => $subscription->product,
                    'device' => $subscription->device,
                    'admin_commission' => $subscription->admin_commission,
                ];
    
                //add to user package from here
                $months = ($request->monthly) ? 1 : 12;
                $userPackageData = $payment_details;
                $userPackageData['enroll_date'] = now();
                $userPackageData['expired_date'] = Carbon::now()->addMonths($months);
                $userPackageData['status'] = PACKAGE_STATUS_ACTIVE;
                UserPackage::create($userPackageData);
            
                $this->showToastrMessage('success', __('Payment has been completed'));
                DB::commit();
                return redirect()->route('subscription.thank-you');
            }
            catch(\Exception $e){
                DB::rollBack();
                return redirect()->back();
            }
        }

        if(auth()->user()->role == USER_ROLE_STUDENT){
            $data['user'] = auth()->user()->student;
        }
        else if(auth()->user()->role == USER_ROLE_INSTRUCTOR){
            $data['user'] = auth()->user()->instructor;
        }
        else if(auth()->user()->role == USER_ROLE_ORGANIZATION){
            $data['user'] = auth()->user()->organization;
        }

        $data['countries'] = Country::orderBy('country_name', 'asc')->get();
        $data['banks'] = Bank::orderBy('name', 'asc')->where('status', 1)->get();

        if (old('country_id')) {
            $data['states'] = State::where('country_id', old('country_id'))->orderBy('name', 'asc')->get();
        }

        if (old('state_id')) {
            $data['cities'] = City::where('state_id', old('state_id'))->orderBy('name', 'asc')->get();
        }

        $razorpay_grand_total_with_conversion_rate = ($data['price'] + get_platform_charge($data['price'])) * (get_option('razorpay_conversion_rate') ? get_option('razorpay_conversion_rate') : 0);
        $data['razorpay_grand_total_with_conversion_rate'] = (float)preg_replace("/[^0-9.]+/", "", number_format($razorpay_grand_total_with_conversion_rate, 2));

        $paystack_grand_total_with_conversion_rate = ($data['price'] + get_platform_charge($data['price'])) * (get_option('paystack_conversion_rate') ? get_option('paystack_conversion_rate') : 0);
        $data['paystack_grand_total_with_conversion_rate'] = (float)preg_replace("/[^0-9.]+/", "", number_format($paystack_grand_total_with_conversion_rate, 2));

        $sslcommerz_grand_total_with_conversion_rate = ($data['price'] + get_platform_charge($data['price'])) * (get_option('sslcommerz_conversion_rate') ? get_option('sslcommerz_conversion_rate') : 0);
        $data['sslcommerz_grand_total_with_conversion_rate'] = (float)preg_replace("/[^0-9.]+/", "", number_format($sslcommerz_grand_total_with_conversion_rate, 2));

        return view('frontend.student.subscription.checkout', $data);
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

            $package = Package::where('uuid', $request->package_uuid)->first();
            $payment = $this->placeOrder($package, $request->payment_method, $request);

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

            //add to user package from here
            $months = ($request->subscription_type) ? 1 : 12;
            $userPackageData = json_decode($payment->payment_details, true);
            $userPackageData['payment_id'] = $payment->id;
            $userPackageData['enroll_date'] = now();
            $userPackageData['expired_date'] = Carbon::now()->addMonths($months);

            UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->where('package_type', $package->package_type)->where('user_packages.user_id', auth()->id())->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->update(['user_packages.status' => PACKAGE_STATUS_CANCELED]);
            UserPackage::create($userPackageData);

            /** ====== Send notification =========*/
            $text = __("Subscription purchase completed");
            $this->send($text, 3, null , auth()->id());

            $text = __("Subscription has been sold");
            $target_url = ($package->package_type == PACKAGE_TYPE_SUBSCRIPTION) ? route('admin.subscriptions.purchase_list') : route('admin.saas.purchase_list');
            $this->send($text, 1, $target_url, null);
            /** ====== Send notification =========*/
            $this->showToastrMessage('success', 'Payment has been completed');

            DB::commit();

            return redirect()->route('subscription.thank-you');

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
            if (empty(get_option('braintree_public_key'))) {
                $this->showToastrMessage('error', 'Braintree payment gateway is off!');
                return redirect()->back();
            }

            $conversion_rate = (get_option('braintree_conversion_rate') ? get_option('braintree_conversion_rate') : 0);
            $currency = get_option('braintree_currency');
        }

        $package = Package::where('uuid', $request->package_uuid)->first();
        $payment = $this->placeOrder($package, $request->payment_method, $request);

        /** order billing address */

        if(auth()->user()->role == USER_ROLE_STUDENT && auth()->user()->student){
            $student = auth()->user()->student;
            $student->fill($request->all());
            $student->save();
        }
        else if(auth()->user()->role == USER_ROLE_INSTRUCTOR && auth()->user()->instructor){
            $student = auth()->user()->instructor;
            $student->fill($request->all());
            $student->save();
        }
        else if(auth()->user()->role == USER_ROLE_ORGANIZATION && auth()->user()->organization){
            $student = auth()->user()->organization;
            $student->fill($request->all());
            $student->save();
        }

        if ($request->payment_method == BANK) {
            $deposit_by = $request->deposit_by;
            $deposit_slip = $this->uploadFileWithDetails('bank', $request->deposit_slip);
            if (!$deposit_slip['is_uploaded']) {
                $this->showToastrMessage('error', 'Something went wrong! Failed to upload file');
                return redirect()->back();
            }

            $payment->payment_status = 'pending';
            $payment->deposit_by = $deposit_by;
            $payment->deposit_slip = $deposit_slip['path'];
            $payment->payment_method = 'bank';
            $payment->bank_id = $request->bank_id;
            $payment->save();

            //add to user package from here
            $months = ($request->subscription_type) ? 1 : 12;
            $userPackageData = json_decode($payment->payment_details, true);
            $userPackageData['payment_id'] = $payment->id;
            $userPackageData['enroll_date'] = now();
            $userPackageData['expired_date'] = Carbon::now()->addMonths($months);
            $userPackageData['status'] = PACKAGE_STATUS_PENDING;
            UserPackage::create($userPackageData);

            /** ====== Send notification =========*/
            $text = __("New subscription purchase request");
            $target_url = ($package->package_type == PACKAGE_TYPE_SUBSCRIPTION) ? route('admin.subscriptions.purchase_pending_list') : route('admin.saas.purchase_pending_list');
            $this->send($text, 1, $target_url, null);
            /** ====== Send notification =========*/
            $this->showToastrMessage('success', 'Request has been Placed! Please Wait for Approve');
            return redirect()->route('subscription.thank-you');
        } else if ($request->payment_method == SSLCOMMERZ)  {

            $total = $payment->grand_total * (get_option('sslcommerz_conversion_rate') ? get_option('sslcommerz_conversion_rate') : 0);
            $total = number_format($total, 2,'.','');
            # CUSTOMER INFORMATION
            $post_data = array();
            $post_data['tran_id'] = $payment->uuid; // tran_id must be unique
            $post_data['product_category'] = "Payment for purchase";

            $post_data['cus_name'] = auth()->user()->name;
            $post_data['cus_phone'] = $request->input('phone_number',$student->address);
            $post_data['cus_email'] = $request->input('email',$student->user->email);
            $post_data['cus_add1'] = $request->input('address',$student->address);
            $post_data['cus_add2'] = "";
            $post_data['cus_city'] = "";
            $post_data['cus_state'] = "";
            $post_data['cus_postcode'] = $request->input('postal_code','017');
            $post_data['cus_country'] = @$student->country->country_name ?? 'BD';
            $post_data['cus_fax'] = "";

            # SHIPMENT INFORMATION
            $post_data['ship_name'] = get_option('app_name') ?? 'LMS Store';
            $post_data['ship_add1'] = $request->input('phone_number',$student->address);
            $post_data['ship_add2'] =  '';
            $post_data['ship_city'] =  '';
            $post_data['ship_state'] =  '';
            $post_data['ship_postcode'] = '';
            $post_data['ship_phone'] = $request->input('phone_number',$student->address);
            $post_data['ship_country'] = @$student->country->country_name ?? 'BD';

            $post_data['shipping_method'] = "NO";
            $post_data['product_name'] = "Course Buy";

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
                'successUrl' => route('paymentNotify.subscription', $payment->uuid)
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

                    //add to user package from here
                    $months = ($request->subscription_type) ? 1 : 12;
                    $userPackageData = json_decode($payment->payment_details, true);
                    $userPackageData['payment_id'] = $payment->id;
                    $userPackageData['enroll_date'] = now();
                    $userPackageData['expired_date'] = Carbon::now()->addMonths($months);

                    UserPackage::join('packages', 'packages.id', '=', 'user_packages.package_id')->where('package_type', $package->package_type)->where('user_packages.user_id', auth()->id())->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->update(['user_packages.status' => PACKAGE_STATUS_CANCELED]);
                    UserPackage::create($userPackageData);

                    /** ====== Send notification =========*/
                    $text = __("Subscription purchase completed");
                    $this->send($text, 3, null , auth()->id());

                    $text = __("Subscription has been sold");
                    $target_url = ($package->package_type == PACKAGE_TYPE_SUBSCRIPTION) ? route('admin.subscriptions.purchase_list') : route('admin.saas.purchase_list');
                    $this->send($text, 1, $target_url, null);
                    /** ====== Send notification =========*/
                    $this->showToastrMessage('success', 'Payment has been completed');
                    return redirect()->route('subscription.thank-you');
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
                'successUrl' => route('paymentNotify.subscription', $payment->uuid)
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

    private function placeOrder(Package $package, $payment_method, $request)
    {
        $price = ($request->subscription_type) ? $package->discounted_monthly_price : $package->discounted_yearly_price;
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
            'package_id' => $package->id,
            'subscription_type' => ($request->subscription_type) ? SUBSCRIPTION_TYPE_MONTHLY : SUBSCRIPTION_TYPE_YEARLY,
            'student' => $package->student,
            'instructor' => $package->instructor,
            'course' => $package->course,
            'consultancy' => $package->consultancy,
            'subscription_course' => $package->subscription_course,
            'bundle_course' => $package->bundle_course,
            'product' => $package->product,
            'device' => $package->device,
            'admin_commission' => $package->admin_commission,
        ]);

        $payment = ModelPayment::create($data);

        return $payment;
    }

    public function thankYou()
    {
        $data['pageTitle'] = __('Subscribe');

        return view('frontend.thankyou_subscribe', $data);
    }

    public function subscriptionList()
    {
        $data['pageTitle'] = __('Subscription panel');
        $data['mySubscriptionPackage'] = UserPackage::where('user_packages.user_id', auth()->id())->where('user_packages.status', PACKAGE_STATUS_ACTIVE)->whereDate('enroll_date', '<=', now())->whereDate('expired_date', '>=', now())->where('package_type', PACKAGE_TYPE_SUBSCRIPTION)->join('packages', 'packages.id', '=', 'user_packages.package_id')->select('package_id', 'package_type', 'subscription_type')->first();
        $data['subscriptions'] = Package::where('package_type', PACKAGE_TYPE_SUBSCRIPTION)->where('status', PACKAGE_STATUS_ACTIVE)->orderBy('order', 'ASC')->get();
        return view('frontend.subscription.list', $data);
    }

    public function subscriptionPlan()
    {
        $data['pageTitle'] = __('Subscription Plan');
        $data['userPackages'] = UserPackage::query()
            ->with('package')
            ->where('user_packages.user_id', auth()->id())
            ->where('package_type', PACKAGE_TYPE_SUBSCRIPTION)
            ->join('packages', 'packages.id', '=', 'user_packages.package_id')
            ->orderBy('user_packages.id','desc')
            ->select('user_packages.*')
            ->get();

        return view('frontend.student.subscription.plan', $data);
    }

    public function subscriptionPlanDetails($id)
    {
        $data['pageTitle'] = __('Subscription Details');
        $data['userPackage'] = UserPackage::query()
            ->where('user_packages.user_id', auth()->id())
            ->where('package_type', PACKAGE_TYPE_SUBSCRIPTION)
            ->join('packages', 'packages.id', '=', 'user_packages.package_id')
            ->select('user_packages.*')
            ->findOrFail($id);
        $data['courseCount'] = Enrollment::whereNotNull('course_id')->whereNull('bundle_id')->where('user_id', auth()->id())->where('user_package_id', $data['userPackage']->id)->count();
        $data['consultancyCount'] = Enrollment::whereNotNull('consultation_slot_id')->where('user_id', auth()->id())->where('user_package_id', $data['userPackage']->id)->count();
        $data['bundleCourseCount'] = Enrollment::whereNotNull('bundle_id')->where('user_id', auth()->id())->where('user_package_id', $data['userPackage']->id)->count();
        $data['deviceCount'] = count(auth()->user()->device);
        return view('frontend.student.subscription.plan_details', $data);
    }
}
