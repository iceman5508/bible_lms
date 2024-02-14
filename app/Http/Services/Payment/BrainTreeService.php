<?php

namespace App\Http\Services\Payment;

use Illuminate\Support\Facades\Log;
use Omnipay\Omnipay;
use Illuminate\Support\Str;

class BrainTreeService extends BasePaymentService
{
    public $successUrl;
    public $cancelUrl;
    public $currency;
    protected $test_mode;
    protected $merchant_id;
    protected $public_key;
    protected $private_key;
    protected $gateway;
    protected $memo;

    public function __construct($object)
    {
        if (isset($object['id'])) {
           $this->cancelUrl = isset($object['cancelUrl ']) ? $object['cancelUrl '] : route('paymentCancel', $object['id']);
            $this->successUrl = isset($object['successUrl']) ? $object['successUrl'] : route('paymentNotify', $object['id']);
        }

        $this->test_mode = get_option('braintree_test_mode');
        $this->merchant_id = get_option('braintree_merchant_id');
        $this->public_key = get_option('braintree_public_key');
        $this->private_key = get_option('braintree_private_key');

        $this->currency = $object['currency'];

        $this->gateway = Omnipay::create('Braintree');

        $this->gateway->setMerchantId($this->merchant_id);
        $this->gateway->setPublicKey($this->public_key);
        $this->gateway->setPrivateKey($this->private_key);
        $this->gateway->setTestMode($this->test_mode);
        $this->memo = Str::random(16);
    }

    public function makePayment($amount, $data= NULL)
    {
        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = '';
        try {
            $user = auth()->user();

            $card = [
                'email' => $user->email,
                'billingFirstName' => $user->name,
                'billingLastName' => '',
                'billingPhone' => $user->mobile,
                'billingCompany' => get_option('app_name', 'LMSZAI'),
                'billingAddress1' => '',
                'billingCity' => '',
                'billingPostcode' => '',
                'billingCountry' => '',
            ];
    
            $reqData =  [
                'transactionId' => $this->memo,
                'amount' => $amount,
                'currency' => $this->currency,
                'testMode' => $this->test_mode,
                'returnUrl' => $this->successUrl,
                'cancelUrl' =>  $this->cancelUrl,
                'notifyUrl' => $this->successUrl,
                'card' => $card,
            ];
            

            $reqData['token'] = $this->gateway->clientToken()->send()->getToken();
            $response = $this->gateway->purchase($reqData)->send();

            if ($response->isRedirect()) {
                $data['redirect_url'] = $response->redirect();
                $data['payment_id'] =  $this->memo;
                $data['message'] = 'Successfully Initiate';
                $data['success'] = true;
                Log::info(json_encode($data));
            }

            return  $data;
          
        } catch (\Exception $ex) {
            $data['message'] = $ex->getMessage();
            return $data;
        }
    }

    public function paymentConfirmation($payment_id, $payer_id = null)
    {

        $data['success'] = false;
        $data['data'] = null;

        $payment = $this->gateway->payments->get($payment_id);
        if ($payment->isPaid()) {
            $data['success'] = true;
            $data['data']['amount'] = $payment->amount->value;
            $data['data']['currency'] = $payment->amount->currency;
            $data['data']['payment_status'] =  'success' ;
            $data['data']['payment_method'] = BRAINTREE;
            // Store in your local database that the transaction was paid successfully
        } elseif ($payment->isCanceled() || $payment->isExpired()) {
            $data['success'] = false;
            $data['data']['amount'] = $payment->amount->value;
            $data['data']['currency'] = $payment->amount->currency;
            $data['data']['payment_status'] =  'unpaid' ;
            $data['data']['payment_method'] = BRAINTREE;
        }else{
            $data['success'] = false;
            $data['data']['amount'] = $payment->amount->value;
            $data['data']['currency'] = $payment->amount->currency;
            $data['data']['payment_status'] =  'unpaid' ;
            $data['data']['payment_method'] = BRAINTREE;
        }

        return $data;
    }

}
