@extends('frontend.layouts.app')

@section('content')
    <div class="bg-page">
        <!-- Page Header Start -->
        <header class="page-banner-header blank-page-banner-header gradient-bg position-relative">
            <div class="section-overlay">
                <div class="blank-page-banner-wrap">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12">
                                <div class="page-banner-content text-center">
                                    <h3 class="page-banner-heading color-heading pb-15">{{ __('Recharge') }}</h3>

                                    <!-- Breadcrumb Start-->
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb justify-content-center">
                                            <li class="breadcrumb-item font-14"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                                            <li class="breadcrumb-item font-14"><a href="{{ route('wallet./') }}">{{ __('Wallet') }}</a></li>
                                            <li class="breadcrumb-item font-14 active" aria-current="page">
                                                {{ __('Recharge') }}</li>
                                        </ol>
                                    </nav>
                                    <!-- Breadcrumb End-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Page Header End -->

        <!-- Cart Page Area Start -->
        <section class="checkout-page">
            <div class="container">
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <strong>{{ __('Error!') }}</strong> {{ $message }}
                    </div>
                @endif

                <form method="post" action="{{ route('wallet.wallet_recharge.pay') }}" data-cc-on-file="false"
                    data-stripe-publishable-key="{{ get_option('STRIPE_PUBLIC_KEY') }}" id="payment-form"
                    class="require-validation" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="amount" value="{{ $amount }}">
                    <div class="stripeToken"></div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="checkout-page-left-part">
                                <div class="payment-method-box bg-white">
                                    <h6 class="font-16 font-medium color-heading mb-30">{{ __('Payment Method') }}
                                    </h6>

                                    @if (get_option('paypal_status') == 1)
                                        <div class="form-check payment-method-card-box paypal-box mb-15">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                value="paypal" {{ old('payment_method') == 'paypal' ? 'checked' : '' }}
                                                id="paypalPayment">
                                            <label class="form-check-label" for="paypalPayment">
                                                <span>
                                                    <span class="font-16 color-heading font-medium me-3">PayPal</span>
                                                    <span class="font-14">{{ __('You will be redirected to the PayPal website after submitting your order') }}</span>
                                                </span>
                                                <span class="payment-card-list">
                                                    <img src="{{ asset('frontend/assets/img/student-profile-img/payment-paypal.png') }}"
                                                        alt="paypal">
                                                </span>
                                            </label>
                                        </div>
                                    @endif

                                    @if (get_option('stripe_status') == 1)
                                        <div class="form-check payment-method-card-box other-payment-box mb-15">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                value="stripe" {{ old('payment_method') == 'stripe' ? 'checked' : '' }}
                                                id="stripePayment">
                                            <label class="form-check-label" for="stripePayment">
                                                <span class="font-16 color-heading font-medium">{{ __('Pay with Credit Card') }}</span>
                                                <span class="payment-card-list">
                                                    <img src="{{ asset('frontend/assets/img/student-profile-img/payment-visa.png') }}"
                                                        alt="payment">
                                                    <img src="{{ asset('frontend/assets/img/student-profile-img/payment-discover.png') }}"
                                                        alt="payment">
                                                    <img src="{{ asset('frontend/assets/img/student-profile-img/payment-janina1.png') }}"
                                                        alt="payment">
                                                    <img src="{{ asset('frontend/assets/img/student-profile-img/payment-mastercard.png') }}"
                                                        alt="payment">
                                                </span>
                                            </label>
                                            <div class="payment-method-card-info-box">
                                                <div class="row">
                                                    <div class="col-md-6 mb-30">
                                                        <label
                                                            class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Card Number') }}</label>
                                                        <input type="text" class="form-control card-number"
                                                            placeholder="{{ __('1234 5678 9101 3456') }}">
                                                    </div>
                                                    <div class="col-md-6 mb-30">
                                                        <label
                                                            class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Card Security Code') }}</label>
                                                        <input type="password" class="form-control card-cvc"
                                                            placeholder="{{ __('Type your security code') }}">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-30">
                                                        <label
                                                            class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Expiration Month') }}</label>
                                                        <input type="text" class="form-control card-expiry-month"
                                                            placeholder="MM">
                                                    </div>
                                                    <div class="col-md-6 mb-30">
                                                        <label
                                                            class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Expiration Year') }}</label>
                                                        <input type="text" class="form-control card-expiry-year"
                                                            placeholder="YY">
                                                    </div>
                                                </div>

                                                <div class="form-row row">
                                                    <div class="col-md-12 d-none error form-group">
                                                        <div class="alert-danger alert  stripe-error-message">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endif

                                    @if (get_option('bank_status') == 1)
                                        <div class="form-check payment-method-card-box other-payment-box pb-0">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                value="bank" {{ old('payment_method') == 'bank' ? 'checked' : '' }}
                                                id="bankPayment">
                                            <label class="form-check-label" for="bankPayment">
                                                <span class="font-16 color-heading font-medium">{{ __('Bank') }}</span>
                                            </label>
                                            <div class="payment-method-card-info-box">
                                                <div class="row">
                                                    <div class="col-md-6 mb-30">
                                                        <label class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Name') }}</label>
                                                        <select name="bank_id" id="bank_id" class="form-control">
                                                            <option value="">{{ __('Select One') }}</option>
                                                            @foreach ($banks as $bank)
                                                                <option value="{{ $bank->id }}">
                                                                    {{ $bank->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-30">
                                                        <label
                                                            class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Account Number') }}</label>
                                                        <input type="text" class="form-control account_number"
                                                            id="account_number" readonly>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-30">
                                                        <label
                                                            class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Deposit By') }}</label>
                                                        <input type="text" class="form-control" placeholder="{{ __('Name') }}" readonly
                                                            name="deposit_by" value="{{ auth()->user()->student->name }}">
                                                    </div>
                                                    <div class="col-md-6 mb-30">
                                                        <label
                                                            class="label-text-title color-heading font-medium font-16 mb-3">{{ __('Deposit Slip') }}</label>
                                                        <input type="file" class="form-control" name="deposit_slip">
                                                    </div>
                                                </div>

                                                <div class="form-row row">
                                                    <div class="col-md-12 d-none error form-group">
                                                        <div class="alert-danger alert  bank-error-message">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endif

                                    @if (get_option('mollie_status') == 1)
                                        <div class="form-check payment-method-card-box other-payment-box pb-0 mt-30">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                value="mollie" {{ old('payment_method') == 'mollie' ? 'checked' : '' }}
                                                id="molliePayment">
                                            <label class="form-check-label mb-0" for="molliePayment">
                                                <span class="font-16 color-heading font-medium">Mollie</span>
                                            </label>
                                        </div>
                                    @endif

                                    @if (get_option('im_status') == 1)
                                        <div class="form-check payment-method-card-box other-payment-box pb-0 mt-30">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                value="instamojo"
                                                {{ old('payment_method') == 'instamojo' ? 'checked' : '' }}
                                                id="instamojoPayment">
                                            <label class="form-check-label mb-0" for="instamojoPayment">
                                                <span class="font-16 color-heading font-medium">Instamojo</span>
                                            </label>
                                        </div>
                                    @endif

                                    @if (get_option('razorpay_status') == 1)
                                        <div class="form-check payment-method-card-box other-payment-box pb-0 mt-30">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                value="razorpay"
                                                {{ old('payment_method') == 'razorpay' ? 'checked' : '' }}
                                                id="razorpayPayment">
                                            <label class="form-check-label mb-0" for="razorpayPayment">
                                                <span class="font-16 color-heading font-medium">Razorpay</span>
                                            </label>
                                        </div>
                                    @endif

                                    @if (get_option('paystack_status') == 1)
                                    <div class="form-check payment-method-card-box other-payment-box pb-0 mt-30">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                               value="paystack"
                                               {{ old('payment_method') == 'paystack' ? 'checked' : '' }}
                                               id="paystackPayment">
                                        <label class="form-check-label mb-0" for="paystackPayment">
                                            <span class="font-16 color-heading font-medium">Paystack</span>
                                        </label>
                                    </div>
                                    @endif

                                    @if (get_option('sslcommerz_status') == 1)
                                        <div class="form-check payment-method-card-box other-payment-box pb-0 mt-30">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                value="sslcommerz"
                                                {{ old('payment_method') == 'sslcommerz' ? 'checked' : '' }}
                                                id="sslcommerzPayment">
                                            <label class="form-check-label mb-0" for="sslcommerzPayment">
                                                <span class="font-16 color-heading font-medium">SSLCOMMERZ</span>
                                            </label>
                                        </div>
                                    @endif
                                    @if (get_option('mercado_status') == 1)
                                        <div class="form-check payment-method-card-box other-payment-box pb-0 mt-30">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                value="mercadopago"
                                                {{ old('payment_method') == 'mercadopago' ? 'checked' : '' }}
                                                id="mercadopagoPayment">
                                            <label class="form-check-label mb-0" for="merPayment">
                                                <span class="font-16 color-heading font-medium">MERCADO PAGO</span>
                                            </label>
                                        </div>
                                    @endif
                                    @if (get_option('flutterwave_status') == 1)
                                        <div class="form-check payment-method-card-box other-payment-box pb-0 mt-30">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                value="flutterwave"
                                                {{ old('payment_method') == 'flutterwave' ? 'checked' : '' }}
                                                id="flutterwavePayment">
                                            <label class="form-check-label mb-0" for="merPayment">
                                                <span class="font-16 color-heading font-medium">Flutterwave</span>
                                            </label>
                                        </div>
                                    @endif
                                    @if (get_option('coinbase_status') == 1)
                                    <div class="form-check payment-method-card-box other-payment-box pb-0 mt-30">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                               value="coinbase"
                                               {{ old('payment_method') == 'coinbase' ? 'checked' : '' }}
                                               id="coinbasePayment">
                                        <label class="form-check-label mb-0" for="coinbasePayment">
                                            <span class="font-16 color-heading font-medium">Coinbase</span>
                                        </label>
                                    </div>
                                    @endif

                                    @if (get_option('zitopay_status') == 1)
                                    <div class="form-check payment-method-card-box other-payment-box pb-0 mt-30">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                               value="zitopay"
                                               {{ old('payment_method') == 'zitopay' ? 'checked' : '' }}
                                               id="zitopayPayment">
                                        <label class="form-check-label mb-0" for="zitopayPayment">
                                            <span class="font-16 color-heading font-medium">ZitoPay</span>
                                        </label>
                                    </div>
                                    @endif
                                    
                                    @if (get_option('iyzipay_status') == 1)
                                    <div class="form-check payment-method-card-box other-payment-box pb-0 mt-30">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                               value="iyzipay"
                                               {{ old('payment_method') == 'iyzipay' ? 'checked' : '' }}
                                               id="iyzipayPayment">
                                        <label class="form-check-label mb-0" for="iyzipayPayment">
                                            <span class="font-16 color-heading font-medium">Iyzico</span>
                                        </label>
                                    </div>
                                    @endif
                                  
                                    @if (get_option('bitpay_status') == 1)
                                    <div class="form-check payment-method-card-box other-payment-box pb-0 mt-30">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                               value="bitpay"
                                               {{ old('payment_method') == 'bitpay' ? 'checked' : '' }}
                                               id="bitpayPayment">
                                        <label class="form-check-label mb-0" for="bitpayPayment">
                                            <span class="font-16 color-heading font-medium">Bitpay</span>
                                        </label>
                                    </div>
                                    @endif
                                  
                                    @if (get_option('braintree_status') == 1)
                                    <div class="form-check payment-method-card-box other-payment-box pb-0 mt-30">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                               value="braintree"
                                               {{ old('payment_method') == 'braintree' ? 'checked' : '' }}
                                               id="braintreePayment">
                                        <label class="form-check-label mb-0" for="braintreePayment">
                                            <span class="font-16 color-heading font-medium">Braintree</span>
                                        </label>
                                    </div>
                                    @endif

                                    <div class="checkout-we-protect-content d-flex align-items-center mt-30">
                                        <div class="flex-shrink-0">
                                            <span class="iconify color-hover font-24"
                                                data-icon="ant-design:lock-filled"></span>
                                        </div>
                                        <div class="flex-grow-1 ms-2 font-13">
                                            {{ __('We protect your payment information using encryption to provide bank-level security') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="checkout-page-right-part sticky-top">
                                <div class="checkout-right-side-box checkout-billing-summary-box">

                                    <div class="accordion" id="accordionExample3">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingThree">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#collapseThree" aria-expanded="true"
                                                    aria-controls="collapseThree">
                                                    {{ __('Billing Summary') }}
                                                </button>
                                            </h2>
                                            <div id="collapseThree" class="accordion-collapse collapse show"
                                                aria-labelledby="headingThree" data-bs-parent="#accordionExample3">
                                                <div class="accordion-body">
                                                    <table class="table billing-summary-table">
                                                        <tbody>
                                                            <tr>
                                                                <td>{{ __('Subtotal') }}</td>
                                                                <td>
                                                                    @if (get_currency_placement() == 'after')
                                                                        {{ get_number_format($amount) }}
                                                                        {{ get_currency_symbol() }}
                                                                    @else
                                                                        {{ get_currency_symbol() }}
                                                                        {{ get_number_format($amount) }}
                                                                    @endif

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('Platform Charge') }} </td>
                                                                <td>
                                                                    @if (get_currency_placement() == 'after')
                                                                        {{ get_platform_charge(10) }}
                                                                        {{ get_currency_symbol() }}
                                                                    @else
                                                                        {{ get_currency_symbol() }}
                                                                        {{ get_platform_charge(10) }}
                                                                    @endif
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th scope="col">{{ __('Grand Total') }}</th>
                                                                <th scope="col">
                                                                    @if (get_currency_placement() == 'after')
                                                                        <span
                                                                            class="grand_total">{{get_number_format($amount + get_platform_charge($amount)) }}</span>
                                                                        {{ get_currency_symbol() }}
                                                                    @else
                                                                        {{ get_currency_symbol() }} <span
                                                                            class="grand_total">{{get_number_format($amount + get_platform_charge($amount)) }}</span>
                                                                    @endif
                                                                </th>
                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                    <table class="table billing-summary-table">
                                                        <tbody>
                                                            <tr>
                                                                <td>{{ __('Conversion Rate') }} </td>
                                                                <td>
                                                                    1 {{ get_currency_symbol() }} =
                                                                    <span class="selected_conversation_rate">?</span> <span
                                                                        class="selected_currency"></span>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th scope="col">In<span
                                                                        class="ms-1 gateway_calculated_rate_currency"></span>
                                                                </th>
                                                                <th scope="col" class="gateway_calculated_rate_price">
                                                                </th>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <div class="row mb-30">
                                                        <div class="col-md-12">
                                                            <div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="flexCheckChecked" checked>
                                                                    <label class="form-check-label mb-0"
                                                                        for="flexCheckChecked">
                                                                        Please check to acknowledge our <a
                                                                            href="{{ route('privacy-policy') }}"
                                                                            class="color-hover text-decoration-underline">Privacy
                                                                            & Terms Policy</a>
                                                                    </label>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="row mb-30">
                                                        @if (env('APP_DEMO') == 'active')
                                                            <div class="col-md-12">
                                                                <div class="">
                                                                    <button type="button"
                                                                        class="theme-btn theme-button1 theme-button3 font-15 fw-bold w-100 appDemo">
                                                                        {{ __('Pay') }}
                                                                        <span
                                                                            class="ms-1  gateway_calculated_rate_price"></span><span
                                                                            class="ms-1 gateway_calculated_rate_currency"></span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="col-md-12">
                                                                <div class="sslcz-btn d-none">
                                                                    <input type="hidden" name="">
                                                                    <button
                                                                        class="your-button-class theme-btn theme-button1 theme-button3 font-15 fw-bold w-100"
                                                                        id="sslczPayBtn"
                                                                        postdata="your javascript arrays or objects which requires in backend"

                                                                    >
                                                                        {{ __('Pay') }}
                                                                        {{ @$sslcommerz_grand_total_with_conversion_rate }}
                                                                        {{ get_option('sslcommerz_currency') }}
                                                                    </button>
                                                                </div>

                                                                <div class="regular-btn">
                                                                    <button type="submit"
                                                                        class="theme-btn theme-button1 theme-button3 font-15 fw-bold w-100 ">
                                                                        {{ __('Pay') }}
                                                                        <span
                                                                            class="ms-1  gateway_calculated_rate_price"></span><span
                                                                            class="ms-1 gateway_calculated_rate_currency"></span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                @php
                    $razorpay_pay_amount = $razorpay_grand_total_with_conversion_rate * 100;
                    $orderId = rand();
                @endphp
            </div>
        </section>
        <!-- Cart Page Area End -->
    </div>

    <input type="hidden" class="paypal_currency" value="{{ get_option('paypal_currency') }}">
    <input type="hidden" class="paypal_conversion_rate" value="{{ get_option('paypal_conversion_rate') }}">

    <input type="hidden" class="bank_currency" value="{{ get_option('bank_currency') }}">
    <input type="hidden" class="bank_conversion_rate" value="{{ get_option('bank_conversion_rate') }}">

    <input type="hidden" class="stripe_currency" value="{{ get_option('stripe_currency') }}">
    <input type="hidden" class="stripe_conversion_rate" value="{{ get_option('stripe_conversion_rate') }}">

    <input type="hidden" class="razorpay_currency" value="{{ get_option('razorpay_currency') }}">
    <input type="hidden" class="razorpay_conversion_rate" value="{{ get_option('razorpay_conversion_rate') }}">

    <input type="hidden" class="instamojo_currency" value="{{ get_option('im_currency') }}">
    <input type="hidden" class="instamojo_conversion_rate" value="{{ get_option('im_conversion_rate') }}">

    <input type="hidden" class="mollie_currency" value="{{ get_option('mollie_currency') }}">
    <input type="hidden" class="mollie_conversion_rate" value="{{ get_option('mollie_conversion_rate') }}">

    <input type="hidden" class="sslcommerz_currency" value="{{ get_option('sslcommerz_currency') }}">
    <input type="hidden" class="sslcommerz_conversion_rate" value="{{ get_option('sslcommerz_conversion_rate') }}">

    <input type="hidden" class="mercado_currency" value="{{ get_option('mercado_currency') }}">
    <input type="hidden" class="mercado_conversion_rate" value="{{ get_option('mercado_conversion_rate') }}">

    <input type="hidden" class="flutterwave_currency" value="{{ get_option('flutterwave_currency') }}">
    <input type="hidden" class="flutterwave_conversion_rate" value="{{ get_option('flutterwave_conversion_rate') }}">

    <input type="hidden" class="paystack_currency" value="{{ get_option('paystack_currency') }}">
    <input type="hidden" class="paystack_conversion_rate" value="{{ get_option('paystack_conversion_rate') }}">

    <input type="hidden" class="coinbase_currency" value="{{ get_option('coinbase_currency') }}">
    <input type="hidden" class="coinbase_conversion_rate" value="{{ get_option('coinbase_conversion_rate') }}">

    <input type="hidden" class="zitopay_currency" value="{{ get_option('zitopay_currency') }}">
    <input type="hidden" class="zitopay_conversion_rate" value="{{ get_option('zitopay_conversion_rate') }}">

    <input type="hidden" class="iyzipay_currency" value="{{ get_option('iyzipay_currency') }}">
    <input type="hidden" class="iyzipay_conversion_rate" value="{{ get_option('iyzipay_conversion_rate') }}">

    <input type="hidden" class="bitpay_currency" value="{{ get_option('bitpay_currency') }}">
    <input type="hidden" class="bitpay_conversion_rate" value="{{ get_option('bitpay_conversion_rate') }}">

    <input type="hidden" class="braintree_currency" value="{{ get_option('braintree_currency') }}">
    <input type="hidden" class="braintree_conversion_rate" value="{{ get_option('braintree_conversion_rate') }}">

    <input type="hidden" class="fetchBankRoute" value="{{ route('student.fetchBank') }}">
@endsection

@push('script')

    @if (get_option('sslcommerz_mode') == 'live')
        <script>
            var obj = {};
            obj.cus_name = $('#first_name').val() + $('#last_name').val();
            obj.cus_phone = $('#phone_number').val();
            obj.cus_email = $('#email').val();
            obj.cus_addr1 = $('#address').val();
            obj.postal_code = $('#postal_code').val();

            $('#sslczPayBtn').prop('postdata', obj);
            (function(window, document) {
                var loader = function() {
                    var script = document.createElement("script"),
                        tag = document.getElementsByTagName("script")[0];
                    script.src = "https://seamless-epay.sslcommerz.com/embed.min.js?" + Math.random().toString(36)
                        .substring(7); // USE THIS FOR LIVE
                    tag.parentNode.insertBefore(script, tag);
                };
                window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload",
                    loader);
            })(window, document);
        </script>
    @else
        <script>
            var obj = {};
            obj.cus_name = $('#first_name').val() + $('#last_name').val();
            obj.cus_phone = $('#phone_number').val();
            obj.cus_email = $('#email').val();
            obj.cus_addr1 = $('#address').val();
            obj.postal_code = $('#postal_code').val();
            obj.country_name = $('#country_name').val();

            // $('#sslczPayBtn').prop('postdata', obj);
            // (function(window, document) {
            //     var loader = function() {
            //         var script = document.createElement("script"),
            //             tag = document.getElementsByTagName("script")[0];
            //         script.src = "https://sandbox.sslcommerz.com/embed.min.js?" + Math.random().toString(36).substring(
            //             7); // USE THIS FOR SANDBOX
            //         tag.parentNode.insertBefore(script, tag);
            //     };
            //     console.log(loader);
            //     window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload",
            //         loader);
            // })(window, document);
        </script>
    @endif

    <script src="{{ asset('frontend/assets/js/custom/student-profile.js') }}"></script>
    <script src="https://js.stripe.com/v2/"></script>
    <script src="{{ asset('frontend/assets/js/custom/checkout.js') }}"></script>
@endpush
