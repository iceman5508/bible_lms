<?php


namespace App\Http\Services\Payment;

use Illuminate\Support\Facades\Log;

class BasePaymentService
{
    public  $get_way = null;
    private $provider = null;
    public function __construct($object)
    {
        $this->provider = $object['payment_method'];

        if ($this->provider == PAYPAL) {
            $this->get_way = new PaypalService($object);
        } elseif ($this->provider == STRIPE) {
            $this->get_way = new StripeService($object);
        } elseif ($this->provider == BANK) {
            $conversion_rate = get_option('bank_conversion_rate') ? get_option('bank_conversion_rate') : 0;
        } elseif ($this->provider == MOLLIE) {
                $this->get_way = new MollieService($object);
        }elseif ($this->provider == MERCADOPAGO) {
                $this->get_way = new MarcadoPagoService($object);
        }elseif ($this->provider == FLUTTERWAVE) {
                $this->get_way = new FlutterwaveService($object);
        }elseif ($this->provider == INSTAMOJO) {
            $this->get_way = new InstamojoService($object);
        }elseif ($this->provider == PAYSTAC) {
            $this->get_way = new PaystackService($object);
        }elseif ($this->provider == SSLCOMMERZ) {
            $this->get_way = new SslCommerzService($object);
        }elseif ($this->provider == COINBASE) {
            $this->get_way = new CoinbaseService($object);
        }elseif ($this->provider == ZITOPAY) {
            $this->get_way = new ZitopayService($object);
        }elseif ($this->provider == IYZIPAY) {
            $this->get_way = new IyzipayService($object);
        }elseif ($this->provider == BITPAY) {
            $this->get_way = new BitPayService($object);
        }elseif ($this->provider == BRAINTREE) {
            $this->get_way = new BrainTreeService($object);
        }
    }

    public function makePayment($amount,$post_data=null){
        $res = $this->get_way->makePayment($amount,$post_data);
        Log::info($res);
        return $res;
    }

    public function paymentConfirmation($payment_id,$payer_id=null){
        if(is_null($payer_id)){
            return $this->get_way->paymentConfirmation($payment_id);
        }
        return $this->get_way->paymentConfirmation($payment_id,$payer_id);
    }


//    protected $config;

    /**
     * @since 1.0.0
     * return how them amount need to charge
     * */
//    abstract public function charge_amount($amount);
//    /**
//     * @since 1.0.0
//     * handle payment gateway ipn response
//     * */
//    abstract public function ipn_response(array $args);
//    /**
//     * @since 1.0.0
//     * return customer payment verified data
//     * */
//
//    public function verified_data($args) : array
//    {
//        return array_merge(['status' => 'complete'],$args);
//    }
//    /**
//     * @since 1.0.0
//     * charge customer account by this method
//     * */
//    abstract public function charge_customer(array $args);
//    /**
//     * @since 1.0.0
//     * list of all supported currency by payment gateway
//     * */
//    abstract public function supported_currency_list();
//    /**
//     * charge_currency()
//     * @since 1.0.0
//     * get charge currency for payment gateway
//     * */
//    abstract public function charge_currency();
//    /**
//     * gateway_name()
//     * @since 1.0.0
//     * add payment gateway name
//     * */
//    abstract public function gateway_name();
//    /**
//     * global_currency()
//     * @since 1.0.0
//     * get global currency
//     * */
//    protected static function global_currency(){
//        return config('paymentgateway.global_currency');
//    }
//
//    /**
//     * get_amount_in_usd()
//     * @since 1.0.0
//     * this function return any amount to usd based on user given currency conversation value,
//     * it will not work if admin did not give currency conversation rate
//     * */
//    protected static function get_amount_in_usd($amount){
//        if (empty(self::global_currency())){
//            report("you have not yet set your global currency");
//        }
//        if (self::global_currency() === 'USD'){
//            return $amount;
//        }
//        $payable_amount = self::make_amount_in_usd($amount, self::global_currency());
//        if ($payable_amount < 1) {
//            $called_class_name = static::class;
//            $instance = new $called_class_name();
//
//            return $payable_amount . __('USD amount is not supported by '.$instance->gateway_name());
//        }
//        return $payable_amount;
//    }
//    protected static function make_amount_in_usd($amount,$currency){
//        $output = 0;
//        $all_currency = GlobalCurrency::script_currency_list();
//        foreach ($all_currency as $cur => $symbol) {
//            if ($cur === 'USD') {
//                continue;
//            }
//            if ($cur === $currency) {
//                $exchange_rate = config('paymentgateway.usd_exchange_rate'); // exchange rate
//                $output = $amount * $exchange_rate;
//            }
//        }
//
//        return $output;
//    }
//    /**
//     * get_amount_in_inr()
//     * @since 1.0.0
//     * this function return any amount to usd based on user given currency conversation value,
//     * it will not work if admin did not give currency conversation rate
//     * */
//    protected static function get_amount_in_inr($amount){
//        if (self::global_currency() === 'INR'){
//            return $amount;
//        }
//        $payable_amount = self::make_amount_in_inr($amount, self::global_currency());
//        if ($payable_amount < 1) {
//            $called_class_name = get_called_class();
//            $instance = new $called_class_name();
//
//            return $payable_amount . __('USD amount is not supported by '.$instance->gateway_name());
//        }
//        return $payable_amount;
//    }
//    /**
//     * convert amount to ngn currency base on conversation given by admin
//     * */
//    private static function make_amount_in_inr($amount, $currency)
//    {
//        $output = 0;
//        $all_currency = GlobalCurrency::script_currency_list();
//        foreach ($all_currency as $cur => $symbol) {
//            if ($cur === 'INR') {
//                continue;
//            }
//            if ($cur == $currency) {
//                $exchange_rate = config('paymentgateway.inr_exchange_rate');
//                $output = $amount * $exchange_rate;
//            }
//        }
//
//        return $output;
//    }
//
//    /**
//     * get_amount_in_usd()
//     * @since 1.0.0
//     * this function return any amount to usd based on user given currency conversation value,
//     * it will not work if admin did not give currency conversation rate
//     * */
//    protected static function get_amount_in_ngn($amount){
//        if (self::global_currency() === 'NGN'){
//            return $amount;
//        }
//        $payable_amount = self::make_amount_in_ngn($amount, self::global_currency());
//        if ($payable_amount < 1) {
//            $called_class_name = static::class;
//            $instance = new $called_class_name();
//
//            return $payable_amount . __('USD amount is not supported by '.$instance->gateway_name());
//        }
//        return $payable_amount;
//    }
//
//    /**
//     * get_amount_in_idr()
//     * @since 1.0.0
//     * this function return any amount to usd based on user given currency conversation value,
//     * it will not work if admin did not give currency conversation rate
//     * */
//    protected static function get_amount_in_idr($amount){
//        if (self::global_currency() === 'IDR'){
//            return $amount;
//        }
//        $payable_amount = self::make_amount_in_idr($amount, self::global_currency());
//        if ($payable_amount < 1) {
//            $called_class_name = static::class;
//            $instance = new $called_class_name();
//
//            return $payable_amount . __('amount is not supported by '.$instance->gateway_name());
//        }
//        return $payable_amount;
//    }
//    /**
//     * get_amount_in_brl()
//     * @since 1.0.0
//     * this function return any amount to usd based on user given currency conversation value,
//     * it will not work if admin did not give currency conversation rate
//     * */
//    protected static function get_amount_in_brl($amount){
//        if (self::global_currency() === 'BRL'){
//            return $amount;
//        }
//        $payable_amount = self::make_amount_in_brl($amount, self::global_currency());
//        if ($payable_amount < 1) {
//            $called_class_name = get_called_class();
//            $instance = new $called_class_name();
//
//            return $payable_amount . __('amount is not supported by '.$instance->gateway_name());
//        }
//        return $payable_amount;
//    }
//
//    /**
//     * get_amount_in_zar()
//     * @since 1.0.0
//     * this function return any amount to usd based on user given currency conversation value,
//     * it will not work if admin did not give currency conversation rate
//     * */
//    protected static function get_amount_in_zar($amount){
//        if (self::global_currency() === 'ZAR'){
//            return $amount;
//        }
//        $payable_amount = self::make_amount_in_zar($amount, self::global_currency());
//        if ($payable_amount < 1) {
//            $called_class_name = get_called_class();
//            $instance = new $called_class_name();
//
//            return $payable_amount . __('amount is not supported by '.$instance->gateway_name());
//        }
//        return $payable_amount;
//    }
//
//    /**
//     * convert amount to idr currency base on conversation given by admin
//     * */
//    private static function make_amount_in_idr($amount, $currency)
//    {
//        $output = 0;
//        $all_currency = GlobalCurrency::script_currency_list();
//        foreach ($all_currency as $cur => $symbol) {
//            if ($cur == 'IDR') {
//                continue;
//            }
//            if ($cur == $currency) {
//                $exchange_rate = config('paymentgateway.idr_exchange_rate');
//                $output = $amount * $exchange_rate;
//            }
//        }
//
//        return $output;
//    }
//
//    /**
//     * convert amount to ngn currency base on conversation given by admin
//     * */
//    private static function make_amount_in_ngn($amount, $currency)
//    {
//        $output = 0;
//        $all_currency = GlobalCurrency::script_currency_list();
//        foreach ($all_currency as $cur => $symbol) {
//            if ($cur === 'NGN') {
//                continue;
//            }
//            if ($cur === $currency) {
//                $exchange_rate = config('paymentgateway.ngn_exchange_rate');
//                $output = $amount * $exchange_rate;
//            }
//        }
//
//        return $output;
//    }
//    /**
//     * convert amount to zar currency base on conversation given by admin
//     * */
//    protected static function make_amount_in_zar($amount,$currency){
//        $output = 0;
//        $all_currency = GlobalCurrency::script_currency_list();
//        foreach ($all_currency as $cur => $symbol) {
//            if ($cur === 'ZAR') {
//                continue;
//            }
//            if ($cur == $currency) {
//                $exchange_rate = config('paymentgateway.zar_exchange_rate');
//                $output = $amount * $exchange_rate ;
//            }
//        }
//
//        return $output;
//    }
//    /**
//     * convert amount to brl currency base on conversation given by admin
//     * */
//    protected static function make_amount_in_brl($amount,$currency){
//        $output = 0;
//        $all_currency = GlobalCurrency::script_currency_list();
//        foreach ($all_currency as $cur => $symbol) {
//            if ($cur === 'BRL') {
//                continue;
//            }
//            if ($cur == $currency) {
//                $exchange_rate = config('paymentgateway.brl_exchange_rate');
//                $output = $amount * $exchange_rate ;
//            }
//        }
//
//        return $output;
//    }


}
