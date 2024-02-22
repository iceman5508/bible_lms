<?php


namespace App\Http\Services\Payment;


use Illuminate\Support\Facades\Log;
use Omnipay\Omnipay;

class PaypalService
{
    public  $gateway ;
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
        $paypal_conf = config('paypal');

        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId($paypal_conf['client_id']);
        $this->gateway->setSecret($paypal_conf['secret']);
        if($paypal_conf['settings']['mode'] == 'sandbox'){
            $this->gateway->setTestMode(true);
        }else{
            $this->gateway->setTestMode(false);
        }

    }
    public function makePayment($price){
        $response = $this->gateway->purchase(array(
            'amount' => $price,
            'currency' => $this->currency,
            'returnUrl' => $this->successUrl,
            'cancelUrl' => $this->cancelUrl,
        ))->send();
        Log::info('<<<<<$response->getData()>>>>>');
        Log::info($response->getData());
        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = '';
        try {
            if ($response->isRedirect()) {
                $data['redirect_url'] =  $response->getData()['links'][1]['href'];
                $data['payment_id'] = $response->getData()['id'];
                $data['success'] = true;
            }
            Log::info(json_encode($data));
            return $data;
        } catch (\Exception $ex) {
            return $data['message'] = $ex->getMessage();
        }
    }

    public function paymentConfirmation($payment_id,$payer_id)
    {

        $data['success'] = false;
        $data['data'] = null;

        if ($payment_id && $payer_id){
            $transaction = $this->gateway->completePurchase(array(
                'payer_id'             => $payer_id,
                'transactionReference' => $payment_id,
            ));
            $response = $transaction->send();

            if ($response->isSuccessful()) {
                $arr_body = $response->getData();
                Log::info($response->getData());
                $data['success'] = true;
                $data['data']['amount'] = $arr_body['transactions'][0]['amount']['total'];
                $data['data']['currency'] = $arr_body['transactions'][0]['amount']['currency'];
                $data['data']['payment_status'] = $arr_body['state'] == 'approved' ? 'success' : 'processing';
                $data['data']['payment_method'] = PAYPAL;
            }
        }
        return $data;
    }

}
