(function ($) {
    "use strict";
    $(document).on('click', "#followingId", function () {
        $.ajax({
            type: "GET",
            url: followRoute,
            data: {
                'id': userInstructorId
            },
            dataType: "json",
            success: function (response) {
                toastr.success(response.msg)
                $('#followingId').text('Unfollow')
                $('#followingId').attr('id', 'unFollowingId')
                var followers = parseInt($('#followers').text());
                $('#followers').text(followers+1);
            },
            error: function (response) {
                toastr.error(response.responseJSON.msg)
            }
        });
    })

    $(document).on('click', "#unFollowingId", function () {
        $.ajax({
            type: "GET",
            url: unfollowRoute,
            data: {
                'id': userInstructorId
            },
            dataType: "json",
            success: function (response) {
                toastr.success(response.msg)
                $('#unFollowingId').text('Follow')
                $('#unFollowingId').attr('id', 'followingId')
                var followers = parseInt($('#followers').text());
                $('#followers').text(followers-1);
            },
            error: function () {
                toastr.error(response.responseJSON.msg)
            }
        });
    })
    $(document).on('click', "#unAuthBtnId", function () {
        toastr.error('You are not login!')
    })

})(jQuery)
