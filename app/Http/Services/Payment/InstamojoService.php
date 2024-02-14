<?php


namespace App\Http\Services\Payment;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Omnipay\Omnipay;

class InstamojoService
{
    public  $getway ;
    private $successUrl ;
    private $cancelUrl ;
    private $provider ;
    private $currency ;
    private $apiUrl ;
    private $apiKey ;
    private $apiToken ;

    public function __construct($object)
    {
        if(isset($object['id'])){
           $this->cancelUrl = isset($object['cancelUrl ']) ? $object['cancelUrl '] : route('paymentCancel', $object['id']);
            $this->successUrl = isset($object['successUrl']) ? $object['successUrl'] : route('paymentNotify', $object['id']);
        }
        
        $this->provider = $object['payment_method'];
        $this->currency = $object['currency'];

        $this->apiUrl = get_option('IM_URL');
        $this->apiKey = get_option('IM_API_KEY');
        $this->apiToken = get_option('IM_AUTH_TOKEN');



    }
    public function makePayment($price){

        $payload = array(
            'purpose' => 'Course Purchase',
            'amount' => $price,
            'phone' => Auth::user()->student->phone_number,
            'buyer_name' => Auth::user()->name,
            'redirect_url' => $this->successUrl,
            'send_email' => true,
            'send_sms' => true,
            'email' => Auth::user()->email,
            'allow_repeated_payments' => false
        );

        $response = $this->curl_request($payload,$this->apiUrl,"POST");

        Log::info('<<<<response>>>>');
        Log::info(json_encode($response));

        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = '';
        try {
            if ($response->success) {
                $data['redirect_url'] = $response->payment_request->longurl;
                $data['payment_id'] = $response->payment_request->id;
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
        $url = $this->apiUrl.$payment_id;
        $payment = $this->curl_request([],$url);
        if ($payment->payment_request->status == 'Completed') {
            $data['success'] = true;
            $data['data']['amount'] = $payment->payment_request->amount;
            $data['data']['currency'] = $this->currency;
            $data['data']['payment_status'] =  'success' ;
            $data['data']['payment_method'] = INSTAMOJO;
            // Store in your local database that the transaction was paid successfully
        }else{
            $data['success'] = false;
            $data['data']['amount'] = $payment->payment_request->amount;
            $data['data']['currency'] = $this->currency;
            $data['data']['payment_status'] =  'unpaid' ;
            $data['data']['payment_method'] = INSTAMOJO;
        }
        return $data;


    }

  public  function curl_request($payload,$url,$method='GET'){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                "X-Api-Key:" . $this->apiKey,
                "X-Auth-Token:" . $this->apiToken
            )
        );
        if($method == 'POST'){
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        }
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        return $response;
    }
}
