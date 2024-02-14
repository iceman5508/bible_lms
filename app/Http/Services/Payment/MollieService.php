<?php


namespace App\Http\Services\Payment;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mollie\Api\MollieApiClient;
use Omnipay\Omnipay;

class MollieService
{
    public  $getway ;
    private $successUrl ;
    private $cancelUrl ;
    private $provider ;
    private $currency ;

    public function __construct($object)
    {
        if(isset($object['id'])){
           $this->cancelUrl = isset($object['cancelUrl ']) ? $object['cancelUrl '] : route('paymentCancel', $object['id']);
            $this->successUrl = isset($object['successUrl']) ? $object['successUrl'] : route('paymentNotify', $object['id']);
        }

        $this->provider = $object['payment_method'];
        $this->currency = $object['currency'];

        $this->gateway = new MollieApiClient();
        $this->gateway->setApiKey(env('MOLLIE_KEY'));
    }
    public function makePayment($price){
        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = '';
        try {
            $param = [
                "amount" => [
                    "currency" => $this->currency,
                    "value" => str($price)
                ],
                "description" => Auth::user()->name,
                "redirectUrl" => $this->successUrl,
//            "metadata" => [
//                "order_id" => "12345",
//            ],
            ];
            $payment = $this->gateway->payments->create($param);
            Log::info("payment");
            Log::info(json_encode($payment));


            if ($payment->status) {
                $data['redirect_url'] = $payment->getCheckoutUrl();
                $data['payment_id'] = $payment->id;
                $data['success'] = true;
            }
            Log::info(json_encode($data));
            return $data;
        } catch (\Exception $ex) {
             $data['message'] = $ex->getMessage();
             return $data;
        }
    }

    public function paymentConfirmation($payment_id)
    {

        $data['success'] = false;
        $data['data'] = null;

        $payment = $this->gateway->payments->get($payment_id);
        if ($payment->isPaid()) {
            $data['success'] = true;
            $data['data']['amount'] = $payment->amount->value;
            $data['data']['currency'] = $payment->amount->currency;
            $data['data']['payment_status'] =  'success' ;
            $data['data']['payment_method'] = MOLLIE;
            // Store in your local database that the transaction was paid successfully
        } elseif ($payment->isCanceled() || $payment->isExpired()) {
            $data['success'] = false;
            $data['data']['amount'] = $payment->amount->value;
            $data['data']['currency'] = $payment->amount->currency;
            $data['data']['payment_status'] =  'unpaid' ;
            $data['data']['payment_method'] = MOLLIE;
        }else{
            $data['success'] = false;
            $data['data']['amount'] = $payment->amount->value;
            $data['data']['currency'] = $payment->amount->currency;
            $data['data']['payment_status'] =  'unpaid' ;
            $data['data']['payment_method'] = MOLLIE;
        }


        return $data;
    }

}
