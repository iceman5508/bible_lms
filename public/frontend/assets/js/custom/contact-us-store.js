(function ($) {
    "use strict";
    $(document).on('click','.submitContactUs', function (event) {
        event.preventDefault();
        let name = $('#inputName').val()
        let email = $('#inputEmail').val()
        let contact_us_issue_id = $('.contact_us_issue_id').val()
        let message = $('.message').val()
        let contactStoreRoute = $('.contactStoreRoute').val()

        toastr.options.positionClass = 'toast-bottom-right';

        if (!name){
            toastr.error("Name is required!")
            return
        }

        if (!email){
            toastr.error("Email address is required!")
            return
        }

        if (!message){
            toastr.error("Message field is required!")
            return
        }

        const validateEmail = (email) => {
            return email.match(
                /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            );
        };

        if (!(validateEmail(email))) {
            toastr.error("Email address is invalid!")
            return;
        }

        $.ajax({
            type: "POST",
            url: contactStoreRoute,
            data: { "name": name ,"email": email, "contact_us_issue_id": contact_us_issue_id, "message": message, '_token': $('meta[name="csrf-token"]').attr('content') },
            datatype: "json",
            success: function (response) {
                $('#inputName').val(null)
                $('#inputEmail').val(null)
                $('.contact_us_issue_id').val(null)
                $('.message').val(null)

                toastr.success(response.msg)

            }
        });
    });
})(jQuery)
