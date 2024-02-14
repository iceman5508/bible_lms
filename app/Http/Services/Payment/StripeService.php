<?php


namespace App\Http\Services\Payment;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe;

class StripeService
{
    public  $getway ;
//    private $successUrl ;
//    private $cancelUrl ;
    private $provider ;
    private $currency ;
    private $token ;

    public function __construct($object)
    {
//        if(!isset($object->id)){
//            $this->cancelUrl = route('paymentCancel',$object['id']);
//            $this->successUrl = route('paymentNotify',$object['id']);
//        }
        $this->provider = $object['payment_method'];
        $this->currency = $object['currency'];
        $this->token = $object['token'];
//        $this->gateway = new Stripe();
//        $this->gateway->setApiKey(env('STRIPE_SECRET_KEY'));

//        $mollie =
//        $mollie->setApiKey(env('MOLLIE_KEY'));

    }
    public function makePayment($price){
//        $price = 12.34;
        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = '';
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $payment = Stripe\Charge::create ([
            "amount" => ($price * 100),
            "currency" => $this->currency,
            "source" => $this->token,
            "description" => 'Payment for purchase'
        ]);

        try {
            if ($payment->status == 'succeeded') {
                $data['payment_id'] = $payment->id;
                $data['success'] = true;
                $data['data']['amount'] = int_to_decimal($payment->amount);
                $data['data']['currency'] = $payment->currency;
                $data['data']['payment_status'] =  'success' ;
                $data['data']['payment_method'] = STRIPE;
            }else{
                $data['success'] = false;
                $data['data']['payment_status'] =  'unpaid' ;
                $data['data']['payment_method'] = STRIPE;
            }
        } catch (\Exception $ex) {
            return $data['message'] = $ex->getMessage();
        }
        return $data;
    }

    public function paymentConfirmation($payment_id)
    {

        $data['success'] = false;
        $data['data'] = null;

//        $payment = $this->gateway->payments->get($payment_id);
//        if ($payment->isPaid()) {
//            $data['success'] = true;
//            $data['data']['amount'] = $payment->amount->value;
//            $data['data']['currency'] = $payment->amount->currency;
//            $data['data']['payment_status'] =  'success' ;
//            $data['data']['payment_method'] = MOLLIE;
//            // Store in your local database that the transaction was paid successfully
//        } elseif ($payment->isCanceled() || $payment->isExpired()) {
//            $data['success'] = false;
//            $data['data']['amount'] = $payment->amount->value;
//            $data['data']['currency'] = $payment->amount->currency;
//            $data['data']['payment_status'] =  'unpaid' ;
//            $data['data']['payment_method'] = MOLLIE;
//        }else{
//            $data['success'] = false;
//            $data['data']['amount'] = $payment->amount->value;
//            $data['data']['currency'] = $payment->amount->currency;
//            $data['data']['payment_status'] =  'unpaid' ;
//            $data['data']['payment_method'] = MOLLIE;
//        }


        return $data;
    }

}
