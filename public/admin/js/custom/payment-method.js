(function ($) {
    "use strict";
    // $('.paypal_currency').on('change', function () {
    //     $('.paypal_append_currency').text($(this).val())
    // })
    // $('.paypal_append_currency').text($('.paypal_currency').val())

    // $('.bank_currency').on('change', function () {
    //     $('.bank_append_currency').text($(this).val())
    // })
    // $('.bank_append_currency').text($('.bank_currency').val())
    //
    // $('.stripe_currency').on('change', function () {
    //     $('.stripe_append_currency').text($(this).val())
    // })
    // $('.stripe_append_currency').text($('.stripe_currency').val())
    //
    // $('.razorpay_currency').on('change', function () {
    //     $('.razorpay_append_currency').text($(this).val())
    // })
    // $('.razorpay_append_currency').text($('.razorpay_currency').val())
    //
    // $('.mollie_currency').on('change', function () {
    //     $('.mollie_append_currency').text($(this).val())
    // })
    // $('.mollie_append_currency').text($('.mollie_currency').val())
    //
    // $('.sslcommerz_currency').on('change', function () {
    //     $('.sslcommerz_append_currency').text($(this).val())
    // })
    // $('.sslcommerz_append_currency').text($('.sslcommerz_currency').val())
    //
    // $('.im_currency').on('change', function () {
    //     $('.im_append_currency').text($(this).val())
    // })
    // $('.im_append_currency').text($('.im_currency').val())
    // $('.paystack_currency').on('change', function () {
    //     $('.paystack_append_currency').text($(this).val())
    // })
    // $('.paystack_append_currency').text($('.paystack_currency').val())

    // $('.mercado_currency').on('change', function () {
    //     $('.mercado_append_currency').text($(this).val())
    // })
    // $('.mercado_append_currency').text($('.mercado_currency').val())



    $('.currency').on('change', function () {
        $(this).closest('.payment-getaway').find('.append_currency').text($(this).val())
    })
    $('.currency').trigger("change");


})(jQuery)
