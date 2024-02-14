(function ($) {
    "use strict";
    let loadMorePageCount;
    $(function () {
        loadMorePageCount = 1;
    });

    $(document).on('click', '.loadMore', function () {
        var instructorCoursePaginateRoute = $('.instructorCoursePaginateRoute').val();
        loadMorePageCount++;
        $.ajax({
            type: "POST",
            url: instructorCoursePaginateRoute,
            data: {
                'page': loadMorePageCount,
                '_token': $('meta[name="csrf-token"]').attr('content')
            },
            datatype: "json",
            success: function (res) {
                if(res.status == true){
                    $('#appendInstructorCourses').append(res.data.appendInstructorCourses);
                    if(res.lastPage == true){
                        $("#loadMoreBtn").removeClass('d-block')
                        $("#loadMoreBtn").addClass('d-none')
                    }
                }
            }
        });
    });

    let instructorLoadMorePageCount;
    $(function () {
        instructorLoadMorePageCount = 1;
    });

    $(document).on('click', '.instructorLoadMore', function () {
        var organizationInstructorsPaginateRoute = $('.organizationInstructorsPaginateRoute').val();
        instructorLoadMorePageCount++;
        $.ajax({
            type: "POST",
            url: organizationInstructorsPaginateRoute,
            data: {
                'page': instructorLoadMorePageCount,
                '_token': $('meta[name="csrf-token"]').attr('content')
            },
            datatype: "json",
            success: function (res) {
                if(res.status == true){
                    $('#appendOrganizationInstructors').append(res.data.appendOrganizationInstructors);
                    if(res.lastPage == true){
                        $("#organizationLoadMoreBtn").removeClass('d-block')
                        $("#organizationLoadMoreBtn").addClass('d-none')
                    }
                }
            }
        });
    });
})(jQuery)
