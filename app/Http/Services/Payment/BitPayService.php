<?php

namespace App\Http\Services\Payment;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Vrajroham\LaravelBitpay\LaravelBitpay;

class BitPayService extends BasePaymentService
{
    public $apiKey;
    public $apiSecret;
    public $locale;
    public $IOptions;
    public $successUrl;
    public $cancelUrl;
    public $currency;
    public $username;
    public $memo;

    public function __construct($object)
    {
        if (isset($object['id'])) {
           $this->cancelUrl = isset($object['cancelUrl ']) ? $object['cancelUrl '] : route('paymentCancel', $object['id']);
            $this->successUrl = isset($object['successUrl']) ? $object['successUrl'] : route('paymentNotify', $object['id']);
        }

        config(['laravel-bitpay.network' => get_option('bitpay_mode', 'testnet')]);
        config(['laravel-bitpay.merchant_token' => get_option('bitpay_key', 'null')]);

        $this->currency = $object['currency'];
    }

    public function makePayment($amount, $order_id= NULL)
    {
        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = '';
        try {
            $user = auth()->user();
            $price = $amount;

            $invoice = LaravelBitpay::Invoice();

            $invoice->setItemDesc(get_option('app_name' , 'LMSZAI'));
            $invoice->setItemCode('1234');
            $invoice->setPrice($price);
            $invoice->setOrderId('1234');

            // Create Buyer Instance
            $buyer = LaravelBitpay::Buyer();
            $buyer->setName($user->name);
            $buyer->setEmail($user->email ?? 'email@email.com');
            $buyer->setAddress1('no Address');
            $buyer->setNotify(true);

            $invoice->setBuyer($buyer);

            // Set currency
            $invoice->setCurrency($this->currency);

            $invoice->setRedirectURL($this->successUrl);

            // Create invoice on bitpay server.
            $invoice = LaravelBitpay::createInvoice($invoice);

            $data['redirect_url'] = $invoice->getUrl();
            $data['payment_id'] = $invoice->getId();
            $data['message'] = 'Successfully Initiate';
            $data['success'] = true;
            Log::info(json_encode($data));
            return  $data;
        } catch (\Exception $ex) {
            $data['message'] = $ex->getMessage();
            return $data;
        }
    }

    public function paymentConfirmation($payment_id, $token = null)
    {
        $data['data'] = null;

        $data['success'] = true;
        $data['data']['amount'] = 0;
        $data['data']['currency'] = $this->currency;
        $data['data']['payment_status'] =  'success';
        $data['data']['payment_method'] = BITPAY;
        return $data;
    }
}