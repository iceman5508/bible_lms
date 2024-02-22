'use strict'
$(function () {
    $('input[type=radio][name=payment_method]').change(function () {
        var payment_method = $('input[name="payment_method"]:checked').val();
        var grand_total = parseFloat(($('.grand_total').html()).replace(',', ''));

        if (payment_method === 'sslcommerz') {
            $(".sslcz-btn").removeClass('d-none');
            $(".regular-btn").addClass('d-none');
        } else {
            $(".sslcz-btn").addClass('d-none');
            $(".regular-btn").removeClass('d-none');
        }

        if (payment_method === 'paypal') {
            var rate = $('.paypal_conversion_rate').val();
            var gateway_calculated_rate_price = (parseFloat(grand_total) * parseFloat(rate.replace(',', ''))).toFixed(2);
            var currency = $('.paypal_currency').val();

            $('.selected_conversation_rate').html(rate)
            $('.selected_currency').html(currency)
            $('.gateway_calculated_rate_currency').html(currency)
            $('.gateway_calculated_rate_price').html(gateway_calculated_rate_price)
        }

        if (payment_method === 'bank') {
            var rate = $('.bank_conversion_rate').val();
            var gateway_calculated_rate_price = (parseFloat(grand_total) * parseFloat(rate.replace(',', ''))).toFixed(2);
            var currency = $('.bank_currency').val();

            $('.selected_conversation_rate').html(rate)
            $('.selected_currency').html(currency)
            $('.gateway_calculated_rate_currency').html(currency)
            $('.gateway_calculated_rate_price').html(gateway_calculated_rate_price)
        }

        if (payment_method === 'stripe') {
            var rate = $('.stripe_conversion_rate').val();
            var gateway_calculated_rate_price = (parseFloat(grand_total) * parseFloat(rate.replace(',', ''))).toFixed(2);
            var currency = $('.stripe_currency').val();

            $('.selected_conversation_rate').html(rate)
            $('.selected_currency').html(currency)
            $('.gateway_calculated_rate_currency').html(currency)
            $('.gateway_calculated_rate_price').html(gateway_calculated_rate_price)
        }

        if (payment_method === 'razorpay') {
            var rate = $('.razorpay_conversion_rate').val();
            var gateway_calculated_rate_price = (parseFloat(grand_total) * parseFloat(rate.replace(',', ''))).toFixed(2);
            var currency = $('.razorpay_currency').val();

            $('.selected_conversation_rate').html(rate)
            $('.selected_currency').html(currency)
            $('.gateway_calculated_rate_currency').html(currency)
            $('.gateway_calculated_rate_price').html(gateway_calculated_rate_price)
        }

        if (payment_method === 'instamojo') {
            var rate = $('.instamojo_conversion_rate').val();
            var gateway_calculated_rate_price = (parseFloat(grand_total) * parseFloat(rate.replace(',', ''))).toFixed(2);
            var currency = $('.instamojo_currency').val();

            $('.selected_conversation_rate').html(rate)
            $('.selected_currency').html(currency)
            $('.gateway_calculated_rate_currency').html(currency)
            $('.gateway_calculated_rate_price').html(gateway_calculated_rate_price)
        }

        if (payment_method === 'mollie') {
            var rate = $('.mollie_conversion_rate').val();
            var gateway_calculated_rate_price = (parseFloat(grand_total) * parseFloat(rate.replace(',', ''))).toFixed(2);
            var currency = $('.mollie_currency').val();

            $('.selected_conversation_rate').html(rate)
            $('.selected_currency').html(currency)
            $('.gateway_calculated_rate_currency').html(currency)
            $('.gateway_calculated_rate_price').html(gateway_calculated_rate_price)
        }

        if (payment_method === 'mercadopago') {
            var rate = $('.mercado_conversion_rate').val();
            var gateway_calculated_rate_price = (parseFloat(grand_total) * parseFloat(rate.replace(',', ''))).toFixed(2);
            var currency = $('.mercado_currency').val();

            $('.selected_conversation_rate').html(rate)
            $('.selected_currency').html(currency)
            $('.gateway_calculated_rate_currency').html(currency)
            $('.gateway_calculated_rate_price').html(gateway_calculated_rate_price)
        }
        if (payment_method === 'flutterwave') {
            var rate = $('.flutterwave_conversion_rate').val();
            var gateway_calculated_rate_price = (parseFloat(grand_total) * parseFloat(rate.replace(',', ''))).toFixed(2);
            var currency = $('.flutterwave_currency').val();

            $('.selected_conversation_rate').html(rate)
            $('.selected_currency').html(currency)
            $('.gateway_calculated_rate_currency').html(currency)
            $('.gateway_calculated_rate_price').html(gateway_calculated_rate_price)
        }

        if (payment_method === 'sslcommerz') {
            var rate = $('.sslcommerz_conversion_rate').val();
            var gateway_calculated_rate_price = (parseFloat(grand_total) * parseFloat(rate.replace(',', ''))).toFixed(2);
            var currency = $('.sslcommerz_currency').val();

            $('.selected_conversation_rate').html(rate)
            $('.selected_currency').html(currency)
            $('.gateway_calculated_rate_currency').html(currency)
            $('.gateway_calculated_rate_price').html(gateway_calculated_rate_price)
        }

        if (payment_method === 'paystack') {
            var rate = $('.paystack_conversion_rate').val();
            var gateway_calculated_rate_price = (parseFloat(grand_total) * parseFloat(rate.replace(',', ''))).toFixed(2);
            var currency = $('.paystack_currency').val();

            $('.selected_conversation_rate').html(rate)
            $('.selected_currency').html(currency)
            $('.gateway_calculated_rate_currency').html(currency)
            $('.gateway_calculated_rate_price').html(gateway_calculated_rate_price)
        }

        if (payment_method === 'coinbase') {
            var rate = $('.coinbase_conversion_rate').val();
            var gateway_calculated_rate_price = (parseFloat(grand_total) * parseFloat(rate.replace(',', ''))).toFixed(2);
            var currency = $('.coinbase_currency').val();

            $('.selected_conversation_rate').html(rate)
            $('.selected_currency').html(currency)
            $('.gateway_calculated_rate_currency').html(currency)
            $('.gateway_calculated_rate_price').html(gateway_calculated_rate_price)
        }

        if (payment_method === 'zitopay') {
            var rate = $('.zitopay_conversion_rate').val();
            var gateway_calculated_rate_price = (parseFloat(grand_total) * parseFloat(rate.replace(',', ''))).toFixed(2);
            var currency = $('.zitopay_currency').val();

            $('.selected_conversation_rate').html(rate)
            $('.selected_currency').html(currency)
            $('.gateway_calculated_rate_currency').html(currency)
            $('.gateway_calculated_rate_price').html(gateway_calculated_rate_price)
        }

        if (payment_method === 'iyzipay') {
            var rate = $('.iyzipay_conversion_rate').val();
            var gateway_calculated_rate_price = (parseFloat(grand_total) * parseFloat(rate.replace(',', ''))).toFixed(2);
            var currency = $('.iyzipay_currency').val();

            $('.selected_conversation_rate').html(rate)
            $('.selected_currency').html(currency)
            $('.gateway_calculated_rate_currency').html(currency)
            $('.gateway_calculated_rate_price').html(gateway_calculated_rate_price)
        }

        if (payment_method === 'bitpay') {
            var rate = $('.bitpay_conversion_rate').val();
            var gateway_calculated_rate_price = (parseFloat(grand_total) * parseFloat(rate.replace(',', ''))).toFixed(2);
            var currency = $('.bitpay_currency').val();

            $('.selected_conversation_rate').html(rate)
            $('.selected_currency').html(currency)
            $('.gateway_calculated_rate_currency').html(currency)
            $('.gateway_calculated_rate_price').html(gateway_calculated_rate_price)
        }

        if (payment_method === 'braintree') {
            var rate = $('.braintree_conversion_rate').val();
            var gateway_calculated_rate_price = (parseFloat(grand_total) * parseFloat(rate.replace(',', ''))).toFixed(2);
            var currency = $('.braintree_currency').val();

            $('.selected_conversation_rate').html(rate)
            $('.selected_currency').html(currency)
            $('.gateway_calculated_rate_currency').html(currency)
            $('.gateway_calculated_rate_price').html(gateway_calculated_rate_price)
        }

    });

    var $form = $(".require-validation");
    $('form.require-validation').bind('submit', function (e) {

        var payment_method = $('input[name="payment_method"]:checked').val();
        if (payment_method === 'razorpay') {
            $("#razorpay_payment").submit();
            return false;
        }

        if (payment_method === 'paystack') {
            $("#paystack_payment").submit();
            return false;
        }

        $('form.require-validation').submit();
    });

    $('.appDemo').click(function () {
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.error("This is a demo version! You can get full access after purchasing the application.")
    });

    $('#bank_id').change(function () {
        var bank_id = $('#bank_id').val();
        var fetchBankRoute = $('.fetchBankRoute').val();
        $.ajax({
            type: "GET",
            url: fetchBankRoute,
            data: {'bank_id': bank_id},
            datatype: "json",
            success: function (response) {
                $('.account_number').val(response.account_number);
            },
            error: function (error) {

            },
        });


    });

});
