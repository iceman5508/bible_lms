(function ($) {
    "use strict";
    $(document).on('click', '.addPromotion', function () {
        var course_id = $(this).data('course_id');
        var promotion_id = $('.promotion_id').val();
        var route = $('.addPromotionCourseRoute').val();

        $.ajax({
            type: "GET",
            url: route,
            data: {"course_id": course_id, "promotion_id": promotion_id},
            datatype: "json",
            success: function (response) {
                toastr.options.positionClass = 'toast-bottom-right';
                if (response.status == 409){
                    toastr.error(response.msg);
                }
                if (response.status == 200) {
                    $('.appendAddRemove'+ response.course_id).html(`
                        <button class="btn-action ms-2 btn btn-danger removePromotion" data-course_id="${response.course_id}">
                            <span class="iconify" data-icon="bi:trash"></span>
                        </button>
                    `)
                    toastr.success(response.msg);
                }
            },
            error: function () {
                alert("Error!");
            },
        });

    });

    $(document).on('click', '.removePromotion', function () {
        var course_id = $(this).data('course_id');
        var promotion_id = $('.promotion_id').val();
        var route = $('.removePromotionCourseRoute').val();
        $.ajax({
            type: "GET",
            url: route,
            data: {"course_id": course_id, "promotion_id": promotion_id},
            datatype: "json",
            success: function (response) {
                toastr.options.positionClass = 'toast-bottom-right';

                if (response.status == 404){
                    toastr.error(response.msg);
                }

                if (response.status == 200) {
                    $('.appendAddRemove'+ response.course_id).html(`
                    <button class="btn-action ms-2 btn btn-primary addPromotion" data-course_id="${response.course_id}">
                        <span class="iconify" data-icon="ant-design:plus-square-outlined"></span>
                    </button>
                    `)
                    toastr.info(response.msg);
                }
            },
            error: function () {
                alert("Error!");
            },
        });

    });
})(jQuery)
