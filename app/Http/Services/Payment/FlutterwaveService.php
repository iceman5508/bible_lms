<?php


namespace App\Http\Services\Payment;



use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveService
{
    public  $getway ;
    private $successUrl ;
    private $cancelUrl ;
    private $provider ;
    private $currency ;
    private $order_id ;
    private $public_key ;
    private $client_secret ;
    private $hash ;

    public function __construct($object)
    {
        if(isset($object['id'])){
           $this->cancelUrl = isset($object['cancelUrl ']) ? $object['cancelUrl '] : route('paymentCancel', $object['id']);
            $this->successUrl = isset($object['successUrl']) ? $object['successUrl'] : route('paymentNotify', $object['id']);
            $this->order_id = $object['id'];
        }

        $this->provider = $object['payment_method'];
        $this->currency = $object['currency'];

        $this->public_key = env('FLW_PUBLIC_KEY');
        $this->client_secret = env('FLW_SECRET_KEY');
        $this->hash = env('FLW_SECRET_HASH');



    }

    public function makePayment($price){

        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = '';

        try {
            $this->verify_currency();
            // if($price > 1000){
            //     throw new \Exception(__('We could not process your request due to your amount is higher than the maximum.'));
            // }

            $payment_id = sha1($this->order_id);
            $postData = [
                'payment_options' => 'card,banktransfer',
                'amount' => $price,
                'email' => "",
                'tx_ref' => $payment_id,
                'currency' => $this->currency,
                'redirect_url' => $this->successUrl,
                'customer' => [
                    'email' => auth()->user()->email,
                    "name" => auth()->user()->name
                ],
                'meta' =>  [
                    'metaname' => 'order_id', 'metavalue' => $this->order_id,
                ]
            ];
            $payment = Http::withToken($this->client_secret)->post(
                'https://api.flutterwave.com/v3/payments',
                $postData
            )->json();
            if ($payment['status'] == 'success') {
                $data['redirect_url'] = $payment['data']['link'];
                $data['payment_id'] = $payment_id;
                $data['success'] = true;
                return $data;
            }else{
                throw new \Exception($payment['message']);
            }

        } catch (\Exception $ex) {
            $data['message'] = $ex->getMessage();
            return $data;
        }
    }

    public function paymentConfirmation($payment_id)
    {

        $data['success'] = false;
        $data['data'] = null;

        sleep(10);
        $payment =  Http::withToken($this->client_secret)
            ->get("https://api.flutterwave.com/v3" . "/transactions/?tx_ref=".$payment_id)
            ->json();
        Log::info($payment);
        if ($payment['status'] == 'success' && isset($payment['data'][0]) && $payment['data'][0]['status'] == 'successful') {
            $data['success'] = true;
            $data['data']['amount'] = $payment['data'][0]['amount'];
            $data['data']['currency'] = $payment['data'][0]['currency'];
            $data['data']['payment_status'] =  'success' ;
            $data['data']['payment_method'] = FLUTTERWAVE;
        } else{
            $data['success'] = false;
            $data['data']['currency'] = $this->currency;
            $data['data']['payment_status'] =  'unpaid' ;
            $data['data']['payment_method'] = FLUTTERWAVE;
        }


        return $data;
    }

    public function verify_currency()
    {
        if (in_array($this->currency, $this->supported_currency_list(), true)){
            return true;
        }
        return throw new \Exception($this->currency.__(' is not supported by '.$this->gateway_name()));

    }

    public function supported_currency_list()
    {
        return ['BIF', 'CAD', 'CDF', 'CVE', 'EUR', 'GBP', 'GHS', 'GMD', 'GNF', 'KES', 'LRD', 'MWK', 'MZN', 'NGN', 'RWF', 'SLL', 'STD', 'TZS', 'UGX', 'USD', 'XAF', 'XOF', 'ZMK', 'ZMW', 'ZWD'];
    }


    public function gateway_name()
    {
        return 'flutterwave';
    }




}
