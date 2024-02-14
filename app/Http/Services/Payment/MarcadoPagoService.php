<?php


namespace App\Http\Services\Payment;



class MarcadoPagoService
{
    public  $getway ;
    private $successUrl ;
    private $cancelUrl ;
    private $provider ;
    private $currency ;
    private $order_id ;
    private $client_id ;
    private $client_secret ;
    private $test_mode ;

    public function __construct($object)
    {
        if(isset($object['id'])){
           $this->cancelUrl = isset($object['cancelUrl ']) ? $object['cancelUrl '] : route('paymentCancel', $object['id']);
            $this->successUrl = isset($object['successUrl']) ? $object['successUrl'] : route('paymentNotify', $object['id']);
            $this->order_id = $object['id'];
        }

        $this->provider = $object['payment_method'];
        $this->currency = $object['currency'];

        $this->client_id = env('MERCADO_PAGO_CLIENT_ID');
        $this->client_secret = env('MERCADO_PAGO_CLIENT_SECRET');
        $this->test_mode = env('MERCADO_PAGO_TEST_MODE',true);



    }
    protected function setAccessToken(){
        return \MercadoPago\SDK::setAccessToken($this->client_secret);
    }
    public function makePayment($price){
        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = '';
        try {
            $order_id =  $this->order_id;
            $this->verify_currency();
            $this->setAccessToken();
            $preference = new \MercadoPago\Preference();

            $item = new \MercadoPago\Item();
            $item->id = $order_id;
            $item->title = "";
            $item->quantity = 1;
            $item->unit_price = $price;

            $preference->items = array($item);

            $preference->back_urls = array(
                "success" => $this->successUrl,
                "failure" => $this->successUrl,
                "pending" => $this->successUrl
            );
            $preference->auto_return = "approved";
            $preference->metadata = array(
                "order_id" => $order_id,
            );

            $preference->save();
            $data['redirect_url'] = $preference->init_point;
            $data['payment_id'] = $preference->id;
            $data['success'] = true;
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
        $this->setAccessToken();

        $payment = \MercadoPago\Payment::find_by_id($payment_id);

        if (!is_null($payment) && $payment->status == 'approved') {
            $data['success'] = true;
            $data['data']['amount'] = $payment->transaction_amount;
            $data['data']['currency'] = $this->currency;
            $data['data']['payment_status'] =  'success' ;
            $data['data']['payment_method'] = MERCADOPAGO;
        } else{
            $data['success'] = false;
            $data['data']['currency'] = $this->currency;
            $data['data']['payment_status'] =  'unpaid' ;
            $data['data']['payment_method'] = MERCADOPAGO;
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
        return ['BRL'];
    }


    public function gateway_name()
    {
        return 'mercadopago';
    }




}
