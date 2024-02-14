(function ($) {
    "use strict";
    
    var quantitySelector = ''; 

    $('.apply-coupon-code').on('click', function (){
        var cart_id = $(this).data('id');
        var coupon_code = $(".coupon-code-"+cart_id).val();
        var route = $(this).data('route');

        $.ajax({
            type: "POST",
            url: route,
            data: {'id': cart_id, 'coupon_code': coupon_code, '_token': $('meta[name="csrf-token"]').attr('content')},
            datatype: "json",
            success: function (response) {
                toastr.options.positionClass = 'toast-bottom-right';
                if (response.status === 402) {
                    toastr.error(response.msg)
                }
                if (response.status === 401 || response.status === 404 || response.status === 409){
                    toastr.error(response.msg)
                } else if(response.status === 200) {
                    $('.price-'+cart_id).text(response.price);
                    $('.total').text(response.total);
                    $('.platform-charge').text(response.platform_charge);
                    $('.grand-total').text(response.grand_total);
                    toastr.success(response.msg)
                }
            },
            error: function (error) {
                toastr.options.positionClass = 'toast-bottom-right';
                if (error.status === 401){
                    toastr.error("You need to login first!")
                }
                if (error.status === 403){
                    toastr.error("You don't have permission to add course or product!")
                }

            },
        });
    })

    $(document).on('click', '.increase, .deincrease', function(e){
        window.quantitySelector =$(this).closest('.quantity-part');
        let quantity = $(window.quantitySelector).find('.quantity').val();
        var type = $(this).data('type');
        var cart_id = $(window.quantitySelector).data('id');
        var route = $(window.quantitySelector).data('route');

        setTimeout(function(){
            if(parseInt(quantity) < 2 && type==2){
                toastr.error('Minimum quantity should be 1');
            }
            else{
                $.ajax({
                    type: "POST",
                    url: route,
                    data: {'id': cart_id, 'quantity': quantity, 'type': type, '_token': $('meta[name="csrf-token"]').attr('content')},
                    datatype: "json",
                    success: function (response) {
                        toastr.options.positionClass = 'toast-bottom-right';
                        if (response.status === 402) {
                            toastr.error(response.msg)
                        }
                        if (response.status === 401 || response.status === 404 || response.status === 409){
                            toastr.error(response.msg)
                        } else if(response.status === 200) {
                            $(window.quantitySelector).find('.quantity').val(response.data.quantity);
                            toastr.success(response.msg);
                            location.reload();
                        }
                        if (response.status === 422) {
                            toastr.error(response.msg)
                        }
                    },
                    error: function (error) {
                        toastr.options.positionClass = 'toast-bottom-right';
                        if (error.status === 401){
                            toastr.error("You need to login first!")
                        }
                        if (error.status === 403){
                            toastr.error("You don't have permission to add course or product!")
                        }
                    },
                });
            }
        }, 500);
    });

})(jQuery)
