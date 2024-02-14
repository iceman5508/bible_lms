<?php

namespace App\Http\Services\Payment;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\Log;

class CoinbaseService extends BasePaymentService
{
    private $client_secret;
    private $cancelUrl;
    private $successUrl;
    private $order_id;
    private $provider;
    private $apiDomain;
    private $client;
    private $currency;

    public function __construct($object)
    {
        if(isset($object['id'])){
           $this->cancelUrl = isset($object['cancelUrl ']) ? $object['cancelUrl '] : route('paymentCancel', $object['id']);
            $this->successUrl = isset($object['successUrl']) ? $object['successUrl'] : route('paymentNotify', $object['id']);
            $this->order_id = $object['id'];
        }

        $this->provider = $object['payment_method'];
        $this->currency = $object['currency'];

        $this->client_secret = get_option('coinbase_key');
        
        $this->client = new HttpClient();

        if (get_option('coinbase_mode') == 'sandbox') {
            $this->apiDomain = 'https://api-public.sandbox.exchange.coinbase.com';
        } else {
            $this->apiDomain = 'https://api.commerce.coinbase.com';
        }
    }

    public function makePayment($amount, $postData = NULL)
    {
        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = '';
        try {
            $coinbase_request = $this->client->request(
                'POST',
                $this->apiDomain . '/charges',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'X-CC-Api-Key' => $this->client_secret,
                        'X-CC-Version' => '2018-03-22',
                    ],
                    'body' => json_encode(array_merge_recursive([
                        'name' => get_option('app_name'),
                        'description' => 'You have payment request '.$amount.' '.$this->currency.' from '.get_option('app_name'),
                        'local_price' => [
                            'amount' => $amount,
                            'currency' => $this->currency,
                        ],
                        'pricing_type' => 'fixed_price',
                        'metadata' => [
                            'user' => 'user',
                            'order_id' => $this->order_id,
                            'amount' => $amount,
                            'currency' => $this->currency,
                        ],
                        'redirect_url' => $this->successUrl,
                        'cancel_url' => $this->cancelUrl,
                    ]))
                ]
            );


            $payment = json_decode($coinbase_request->getBody()->getContents());
            Log::info(json_encode($payment));
            if ($payment->hosted_url) {
                $data['success'] = true;
                $data['redirect_url'] = $payment->data->hosted_url;
                $data['payment_id'] = $payment->data->id;
            }
            return $data;
        } catch (\Exception $ex) {
            Log::info($ex->getMessage());
            $data['message'] = $ex->getMessage();
            return $data;
        }
    }

    public function paymentConfirmation($payment_id, $payerId = NULL)
    {
        $data['data'] = null;
        $coinbase_request = $this->client->request(
            'GET',
            'https://api.commerce.coinbase.com/charges/'.$payment_id,
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-CC-Api-Key' => $this->client_secret,
                    'X-CC-Version' => '2018-03-22',
                ],
            ]
        );

        $coinbase = json_decode($coinbase_request->getBody()->getContents());

        if (isset($coinbase->data)) {
            $data['success'] = true;
            $data['data']['amount'] = $coinbase->data->pricing->local->amount;
            $data['data']['currency'] = $coinbase->data->pricing->local->currency;
            $data['data']['payment_status'] =  'success';
            $data['data']['payment_method'] = COINBASE;
        }else {
            $data['success'] = false;
            $data['data']['amount'] = $coinbase->data->pricing->local->amount;
            $data['data']['currency'] = $coinbase->data->pricing->local->currency;
            $data['data']['payment_status'] =  'unpaid';
            $data['data']['payment_method'] = COINBASE;
        }

        return $data;
    }
}
