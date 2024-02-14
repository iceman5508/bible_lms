(function () {
    'use strict'

    $(document).on('click', '.replyBtn', function () {
        let parent_id = $(this).data('parent_id');
        $('.parent_id').val(parent_id)

    });

    $(document).on('click', '.submitComment', function () {
        let blog_id = $('.blog_id').val();
        let user_id = $('.user_id').val();
        let name = $('.user_name').val();
        let email = $('.user_email').val();
        let comment = $('.comment').val()
        let blogCommentStoreRoute = $('.blogCommentStoreRoute').val()

        if (!user_id) {
            toastr.warning("You need to login first!")
            return
        }

        if (!name) {
            toastr.error("Name is required!")
            return
        }

        if (!email) {
            toastr.error("Email address is required!")
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

        if (!comment) {
            toastr.error("Comment field is required!")
            return
        }

        $.ajax({
            type: "POST",
            url: blogCommentStoreRoute,
            data: {
                "blog_id": blog_id,
                "user_id": user_id,
                "name": name, "email": email, "comment": comment,
                '_token': $('meta[name="csrf-token"]').attr('content')
            },
            datatype: "json",
            success: function (response) {
                $('.comment').val(null)
                $('.appendCommentList').html(response)
                toastr.success("Comment successfully.")

            }
        });
    });

})()
