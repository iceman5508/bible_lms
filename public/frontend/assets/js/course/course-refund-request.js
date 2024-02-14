(function ($) {
    "use strict";
    $(document).on('click', '.courseRefund', function(){
        let amount = $(this).attr('data-amount');
        let id = $(this).attr('data-id');
        $('#refund-amount').val(amount);
        $('#refund-id').val(id);
        $("#refundModal").modal("show");
    });


    $(document).on('click', '.submitRefund', function () {
        var studentRefundRequestRoute =$('.studentRefundRequestRoute').val();

        toastr.options.positionClass = 'toast-bottom-right';
        var feedback = $('#refund-feedback').val();
        var id = $('#refund-id').val();
        $('#refund-feedback').val(null)
        if (!feedback) {
            toastr.error("Reason is required")
            return;
        }

        $.ajax({
            type: "POST",
            url: studentRefundRequestRoute,
            data: {'enrollment_id': id, 'reason': feedback, '_token': $('meta[name="csrf-token"]').attr('content')},
            datatype: "json",
            success: function (response) {
                toastr.options.positionClass = 'toast-bottom-right';
                if (response.status == true) {
                    toastr.success(response.message)
                    $('.modal').modal('hide');
                }else if (response.status == false) {
                    toastr.error(response.message)
                }
            },
            error: function (error) {

            },
        });
    });
})(jQuery)
