(function ($) {
    "use strict";
    $(document).on('click', '.addBundle', function () {
        var course_id = $(this).data('course_id');
        var bundle_id = $('.bundle_id').val();
        var route = $('.addBundleCourseRoute').val();
        console.log(course_id, bundle_id, route)
        $.ajax({
            type: "POST",
            url: route,
            data: {"course_id": course_id, "bundle_id": bundle_id, '_token': $('meta[name="csrf-token"]').attr('content')},
            datatype: "json",
            success: function (response) {
                toastr.options.positionClass = 'toast-bottom-right';
                if (response.status == 409){
                    toastr.error(response.msg);
                }

                if (response.status == 200) {
                    $('.totalCourses').html(response.totalCourses);
                    $('.appendAddRemoveBundleCourse'+ response.course_id).html(`
                        <input class="form-check-input removeBundle " type="checkbox" checked data-course_id="${response.course_id}">
                    `)
                    toastr.success(response.msg);
                }
            },
            error: function () {
                alert("Error!");
            },
        });

    });

    $(document).on('click', '.removeBundle', function () {
        var course_id = $(this).data('course_id');
        var bundle_id = $('.bundle_id').val();
        var route = $('.removeBundleCourseRoute').val();
        $.ajax({
            type: "POST",
            url: route,
            data: {"course_id": course_id, "bundle_id": bundle_id, '_token': $('meta[name="csrf-token"]').attr('content')},
            datatype: "json",
            success: function (response) {
                toastr.options.positionClass = 'toast-bottom-right';

                if (response.status == 404){
                    toastr.error(response.msg);
                }

                if (response.status == 200) {
                    $('.totalCourses').html(response.totalCourses);
                    $('.appendAddRemoveBundleCourse'+ response.course_id).html(`
                        <input class="form-check-input addBundle" type="checkbox" data-course_id="${response.course_id}">
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
