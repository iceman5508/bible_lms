<?php

namespace App\Http\Services\Payment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Locale;
use Iyzipay\Model\PaymentGroup;
use Iyzipay\Model\PayWithIyzicoInitialize;
use Iyzipay\Options;
use Iyzipay\Request\CreatePayWithIyzicoInitializeRequest;
use Illuminate\Support\Str;

class IyziPayService extends BasePaymentService
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

        $this->currency = $object['currency'];
        $this->apiKey = get_option('iyzipay_key');
        $this->apiSecret = get_option('iyzipay_secret');

        $this->IOptions = new Options();
        $this->IOptions->setApiKey($this->apiKey);
        $this->IOptions->setSecretKey($this->apiSecret);
        if (get_option('iyzipay_mode') == 'sandbox') {
            $this->IOptions->setBaseUrl('https://sandbox-api.iyzipay.com');
        } else {
            $this->IOptions->setBaseUrl('https://api.iyzipay.com');
        }
        $this->locale = Locale::EN;
        $this->memo = Str::random(16);
    }

    public function makePayment($amount, $postData = null)
    {
        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = '';
        try {
            $IForm = new CreatePayWithIyzicoInitializeRequest();
            $IForm->setLocale($this->locale);
            $IForm->setConversationId($this->memo);
            $IForm->setPrice($amount);
            $IForm->setPaidPrice($amount);
            $IForm->setCurrency($this->currency);
            $IForm->setBasketId($this->memo);
            $IForm->setPaymentGroup(PaymentGroup::PRODUCT);
            $IForm->setCallbackUrl($this->successUrl);

            $IBuyer = new Buyer();
            $IBuyer->setId($this->memo);
            $IBuyer->setName(Auth::user()->name);
            $IBuyer->setSurname(Auth::user()->name);
            $IBuyer->setEmail(Auth::user()->email);
            $IBuyer->setIdentityNumber(Auth::user()->student->uuid);
            $IBuyer->setRegistrationAddress(Auth::user()->address);
            $IBuyer->setIp($_SERVER["REMOTE_ADDR"]);
            $IBuyer->setCity(Auth::user()->student->city->name);
            $IBuyer->setCountry(Auth::user()->student->country->country_name);
            $IBuyer->setZipCode(Auth::user()->student->postal_code);
            $IForm->setBuyer($IBuyer);

            $IShipping = new Address();
            $IShipping->setContactName(Auth::user()->name);
            $IShipping->setCity(Auth::user()->student->city->name);
            $IShipping->setCountry(Auth::user()->student->country->country_name);
            $IShipping->setZipCode(Auth::user()->student->postal_code);
            $IShipping->setAddress(Auth::user()->address);
            $IForm->setShippingAddress($IShipping);

            $IBilling = new Address();
            $IBilling->setContactName(Auth::user()->name);
            $IBilling->setCity(Auth::user()->student->city->name);
            $IBilling->setCountry(Auth::user()->student->country->country_name);
            $IBilling->setZipCode(Auth::user()->student->postal_code);
            $IBilling->setAddress(Auth::user()->address);
            $IForm->setBillingAddress($IBilling);

            $FBasketItems = new BasketItem();
            $FBasketItems->setId($this->memo);
            $FBasketItems->setName(Auth::user()->name. '\'s busket');
            $FBasketItems->setCategory1(Auth::user()->name. '\'s buskets category');
            $FBasketItems->setItemType(BasketItemType::VIRTUAL);
            $FBasketItems->setPrice($amount);

            $IForm->setBasketItems([$FBasketItems]);

            $payWithIyzicoInitialize = PayWithIyzicoInitialize::create($IForm, $this->IOptions);

            Log::info("New");
            Log::info("payment");
            Log::info($amount);
            Log::info(json_encode($payWithIyzicoInitialize));
            $data['redirect_url'] = $payWithIyzicoInitialize->getPayWithIyzicoPageUrl();
            $data['payment_id'] = $this->memo;
            $data['success'] = true;
            Log::info(json_encode($data));
            return $data;
        } catch (\Exception $ex) {
            $data['message'] = $ex->getMessage();
            return $data;
        }
    }

    public function paymentConfirmation($payment_id, $token = null)
    {
        $data['data'] = null;

        $request2 = new \Iyzipay\Request\RetrievePayWithIyzicoRequest();
        $request2->setLocale($this->locale);
        $request2->setConversationId($payment_id);
        $request2->setToken($token);

        $checkoutForm = \Iyzipay\Model\PayWithIyzico::retrieve($request2, $this->IOptions);
        if ($checkoutForm->getStatus() == 'success') {
            $data['success'] = true;
            $data['data']['amount'] = $checkoutForm->getPaidPrice();
            $data['data']['currency'] = $checkoutForm->getCurrency();
            $data['data']['payment_status'] =  'success';
            $data['data']['payment_method'] = IYZIPAY;
        } else {
            $data['success'] = false;
            $data['data']['payment_status'] =  'unpaid';
            $data['data']['payment_method'] = IYZIPAY;
        }

        return $data;
    }
}
