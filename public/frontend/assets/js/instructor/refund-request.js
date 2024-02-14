(function ($) {
    "use strict";
    $(document).on('click', '.status-change-btn', function(){
        let data = $(this).data('data');
        let type = $(this).attr('data-type');
        $('#refund-id').val(data.id);
        $('#refund-type').val(type);
        if(type == 2){
            $('#statusChangeRefund').text('Reject')
            $('#feedback').removeClass('d-none');
        }else{
            $('#statusChangeRefund').text('Approve')
            $('#feedback').addClass('d-none');
        }
        $('#refund-reason').val(data.reason);
        $('#refund-amount').val(data.amount);
        $("#refundModal").modal("show");
    });


    $(document).on('click', '#statusChangeRefund', function () {
        var statusChangeRefundUrl =$('#statusChangeRefundUrl').val();
        var type =$('#refund-type').val();
        toastr.options.positionClass = 'toast-bottom-right';
        var feedback = $('#instructor-feedback').val();
        var id = $('#refund-id').val();
        $('#instructor-feedback').val(null)
        if (!feedback && type == 2) {
            toastr.error("Feedback is required")
            return;
        }

        $.ajax({
            type: "POST",
            url: statusChangeRefundUrl,
            data: {'id': id, 'type': type, 'feedback': feedback, '_token': $('meta[name="csrf-token"]').attr('content')},
            datatype: "json",
            success: function (response) {
                toastr.options.positionClass = 'toast-bottom-right';
                if (response.status == true) {
                    toastr.success(response.message)
                    location.reload();
                }else if (response.status == false) {
                    toastr.error(response.message)
                }
            },
            error: function (error) {

            },
        });
    });
})(jQuery)
