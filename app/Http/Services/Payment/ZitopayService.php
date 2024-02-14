<?php

namespace App\Http\Services\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ZitopayService extends BasePaymentService
{
    public $successUrl ;
    public $cancelUrl ;
    public $currency ;
    public $username;
    public $memo;

    public function __construct($object)
    {
        if(isset($object['id'])){
           $this->cancelUrl = isset($object['cancelUrl ']) ? $object['cancelUrl '] : route('paymentCancel', $object['id']);
            $this->successUrl = isset($object['successUrl']) ? $object['successUrl'] : route('paymentNotify', $object['id']);
        }

        $this->currency = $object['currency'];
        $this->username = get_option('zitopay_username');
        $this->memo = Str::random(16);
    }

    public function makePayment($amount, $postData = NULL){
        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = '';
        try {
            Log::info("payment");
            Log::info($amount);
            $param = "currency=".$this->currency."&amount=". $amount."&ref=".$this->memo."&memo=".$this->memo."&receiver=".$this->username."&success_url=".$this->successUrl."&cancel_url=".$this->cancelUrl;
            $data['redirect_url'] = "https://zitopay.africa/sci/".base64_encode($param);
            $data['payment_id'] = $this->memo;
            $data['success'] = true;
            Log::info(json_encode($data));
            return $data;
        } catch (\Exception $ex) {
            $data['message'] = $ex->getMessage();
            return $data;
        }
    }

    public function paymentConfirmation($payment_id, $payer_id=NULL)
    {
        $data['data'] = null;

        $payment = Http::acceptJson()->asForm()->post('https://zitopay.africa/api_v1',[
                "action" => 'get_transaction',
                'receiver' => $this->username,
                'ref' => $payment_id,
                'trade_id' => 0
            ]);

            if ($payment->ok()){
                $result = $payment->object();
                if (!empty($result) && !property_exists($result,'error')){
                    if ($result->status_msg  === 'COMPLETED'){
                        $data['success'] = true;
                        $data['data']['amount'] = $result->amount;
                        $data['data']['currency'] =$result->currency_code;
                        $data['data']['payment_status'] =  'success';
                        $data['data']['payment_method'] = ZITOPAY;
                    }
                }
            }else{
                $data['success'] = false;
                $data['data']['payment_status'] =  'unpaid';
                $data['data']['payment_method'] = ZITOPAY;
            }

        return $data;
    }
}
