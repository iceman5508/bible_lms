(function ($) {
    "use strict";
    $(document).on('click', '.courseReview', function (){
        var course_id = $(this).data('course_id');
        $('.course_id').val(course_id);
    });

    var ratingSum = 0;
    $(document).on('click', "#btncheck1", function () {
        ratingSum = 1;
        addRemoveClass()
        $('#btncheck1').attr('checked', true)

        $('#btncheck1').addClass('active')
    });

    $(document).on('click', "#btncheck2", function () {
        ratingSum = 2;
        addRemoveClass()
        $('#btncheck1').attr('checked', true)
        $('#btncheck2').attr('checked', true)

        $('#btncheck1').addClass('active')
        $('#btncheck2').addClass('active')
    });

    $(document).on('click', "#btncheck3", function () {
        ratingSum = 3;
        addRemoveClass()
        $('#btncheck1').attr('checked', true)
        $('#btncheck2').attr('checked', true)
        $('#btncheck3').attr('checked', true)

        $('#btncheck1').addClass('active')
        $('#btncheck2').addClass('active')
        $('#btncheck3').addClass('active')
    });

    $(document).on('click', "#btncheck4", function () {
        ratingSum = 4;
        addRemoveClass()
        $('#btncheck1').attr('checked', true)
        $('#btncheck2').attr('checked', true)
        $('#btncheck3').attr('checked', true)
        $('#btncheck4').attr('checked', true)

        $('#btncheck1').addClass('active')
        $('#btncheck2').addClass('active')
        $('#btncheck3').addClass('active')
        $('#btncheck4').addClass('active')
    });

    $(document).on('click', "#btncheck5", function () {
        ratingSum = 5;
        addRemoveClass()
        $('#btncheck1').attr('checked', true)
        $('#btncheck2').attr('checked', true)
        $('#btncheck3').attr('checked', true)
        $('#btncheck4').attr('checked', true)
        $('#btncheck5').attr('checked', true)

        $('#btncheck1').addClass('active')
        $('#btncheck2').addClass('active')
        $('#btncheck3').addClass('active')
        $('#btncheck4').addClass('active')
        $('#btncheck5').addClass('active')
    });

    function addRemoveClass() {
        $('#btncheck1').removeClass('active');
        $('#btncheck2').removeClass('active');
        $('#btncheck3').removeClass('active');
        $('#btncheck4').removeClass('active');
        $('#btncheck5').removeClass('active');

        $('#btncheck1').removeAttr('checked');
        $('#btncheck2').removeAttr('checked');
        $('#btncheck3').removeAttr('checked');
        $('#btncheck4').removeAttr('checked');
        $('#btncheck5').removeAttr('checked');
    }

    $(document).on('click', '.submitReview', function () {
        var course_id =$('.course_id').val();
        var studentReviewCreateRoute =$('.studentReviewCreateRoute').val();

        toastr.options.positionClass = 'toast-bottom-right';

        if (ratingSum == 0) {
            toastr.error("Rating is required")
            return;
        }
        var feedback = $('.feedback').val();

        $('.feedback').val(null)
        if (!feedback) {
            toastr.error("Feedback is required")
            return;
        }
        $('.modal').modal('hide');

        addRemoveClass()

        $.ajax({
            type: "POST",
            url: studentReviewCreateRoute,
            data: {'course_id': course_id, 'rating': ratingSum, 'comment': feedback, '_token': $('meta[name="csrf-token"]').attr('content')},
            datatype: "json",
            success: function (response) {
                toastr.options.positionClass = 'toast-bottom-right';
                if (response.status == 200) {
                    toastr.success(response.msg)
                }else if (response.status == 302) {
                    toastr.error(response.msg)
                }
            },
            error: function (error) {

            },
        });
    });
})(jQuery)
