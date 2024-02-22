<?php

namespace App\Http\Services\Payment;

use Stripe\StripeClient;

class StripeService extends BasePaymentService
{
    public  $stripClient;


    public  $gateway ;
    private $successUrl ;
    private $cancelUrl ;
    private $provider ;
    public $currency ;

    public function __construct($object)
    {
        if(isset($object['id'])){
            $this->cancelUrl = isset($object['cancelUrl ']) ? $object['cancelUrl '] : route('paymentCancel', $object['id']);
            $this->successUrl = isset($object['successUrl']) ? $object['successUrl'] : route('paymentNotify', $object['id']);
        }

        $this->stripClient = new StripeClient(get_option('STRIPE_PUBLIC_KEY'));
        $this->provider = $object['payment_method'];
        $this->currency = $object['currency'];
    }

    public function makePayment($amount, $post_data = null)
    {
        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = '';

        $payment = $this->stripClient->checkout->sessions->create([
            'success_url' => $this->successUrl,
            'cancel_url' => $this->cancelUrl,
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => $this->currency,
                        'product_data' => [
                            'name' => 'Amount',
                        ],
                        'unit_amount' =>$amount * 100,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
        ]);

        try {

            if ($payment->status == 'open') {
                $data['payment_id'] = $payment->payment_intent;
                $data['success'] = true;
                $data['redirect_url'] = $payment->url;
            }

            return $data;
        } catch (\Exception $ex) {
            return $data['message'] = $ex->getMessage();
        }
    }

    public function paymentConfirmation($payment_id, $payer_id=NULL)
    {
        $data['data'] = null;
        $payment = $this->stripClient->paymentIntents->retrieve($payment_id, []);
        if ($payment->status == 'succeeded') {
            $data['success'] = true;
            $data['data']['amount'] = $payment->amount_received;
            $data['data']['currency'] = $payment->currency;
            $data['data']['payment_status'] =  'success';
            $data['data']['payment_method'] = STRIPE;
        } else {
            $data['success'] = false;
            $data['data']['amount'] = $payment->amount;
            $data['data']['currency'] = $payment->currency;
            $data['data']['payment_status'] =  'unpaid';
            $data['data']['payment_method'] = STRIPE;
        }

        return $data;
    }
}
