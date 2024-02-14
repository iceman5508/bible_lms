<?php


namespace App\Http\Services\Payment;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Omnipay\Omnipay;
use Unicodeveloper\Paystack\Facades\Paystack;


class PaystackService
{
    public  $getway ;
    private $successUrl ;
    private $cancelUrl ;
    private $provider ;
    private $currency ;
    private $paymentApiUrl = 'https://api.paystack.co/transaction/initialize' ;
    private $transactionVerifyApiUrl = 'https://api.paystack.co/transaction/verify/' ;
    private $apiKey ;
    private $apiSecret ;
    private $merchantEmail ;
    private $ref ;
    private $id ;

    public function __construct($object)
    {        
        if(isset($object['id'])){
           $this->cancelUrl = isset($object['cancelUrl ']) ? $object['cancelUrl '] : route('paymentCancel', $object['id']);
            $this->successUrl = isset($object['successUrl']) ? $object['successUrl'] : route('paymentNotify', $object['id']);
            $this->id = $object['id'];
        }

        $this->provider = $object['payment_method'];
        $this->currency = $object['currency'];
        if(isset($object['reference'])){
            $this->ref = $object['reference'];
        }

//        $this->apiUrl = get_option('PAYSTACK_PAYMENT_URL');
        $this->apiKey = get_option('PAYSTACK_PUBLIC_KEY');
        $this->apiSecret = get_option('PAYSTACK_SECRET_KEY');
        $this->merchantEmail = env('MERCHANT_EMAIL','marchant@gmail.com');


    }
    public function makePayment($price){

        $payload = array(
            "callback_url" => $this->successUrl,
            "amount" => $price * 100,
            "email" => $this->merchantEmail,
            "currency" => $this->currency,
            "orderID" => $this->id,
        );
        $response = $this->curl_request($payload,$this->paymentApiUrl);

        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = '';
        try {
            if ($response->status) {
                $data['redirect_url'] = $response->data->authorization_url;
                $data['payment_id'] = $response->data->reference;
                $data['success'] = true;
            }
            return $data;
        } catch (\Exception $ex) {
            return $data['message'] = $ex->getMessage();
        }
    }

    public function paymentConfirmation($payment_id)
    {
        $data['success'] = false;
        $data['data'] = null;
        $url = $this->transactionVerifyApiUrl.$payment_id;
        $payment = $this->curl_request([],$url,'GET');
        if ($payment->status && $payment->data->status == 'success' ) {
            $data['success'] = true;
            $data['data']['amount'] = $payment->data->amount;
            $data['data']['currency'] = $this->currency;
            $data['data']['payment_status'] =  'success' ;
            $data['data']['payment_method'] = PAYSTAC;
            // Store in your local database that the transaction was paid successfully
        }else{
            $data['success'] = false;
            $data['data']['amount'] = $payment->data->amount;
            $data['data']['currency'] = $this->currency;
            $data['data']['payment_status'] =  'unpaid' ;
            $data['data']['payment_method'] = PAYSTAC;
        }
        return $data;

    }

  public  function curl_request($payload,$url,$method='POST'){


      $fields_string = http_build_query($payload);

      //open connection
      $ch = curl_init();

      //set the url, number of POST vars, POST data
      curl_setopt($ch,CURLOPT_URL, $url);
      if($method == 'POST') {
          curl_setopt($ch, CURLOPT_POST, true);
      }else{
          curl_setopt($ch,CURLOPT_CUSTOMREQUEST , "GET");
      }
      curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Authorization: Bearer ".$this->apiSecret,
          "Cache-Control: no-cache",
      ));

      //So that curl_exec returns the contents of the cURL; rather than echoing it
      curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

      //execute post
      $result = curl_exec($ch);
      return json_decode($result);
    }
}
