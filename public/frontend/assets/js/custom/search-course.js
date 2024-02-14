(function () {
    'use strict'
    $(document).on('keyup', '#searchCourse',function() {
        var title = $('#searchCourse').val()
        var search_route = $('.search_route').val()

        if (title) {
            $('.searchBox').removeClass('d-none')
            $('.searchBox').addClass('d-block')
        } else {
            $('.searchBox').removeClass('d-block')
            $('.searchBox').addClass('d-none')
        }

        $.ajax({
            type: "GET",
            url: search_route,
            data: {'title': title},
            success: function (response) {
                $('.appendCourseSearchList').html(response);
            }
        });
    });
})()
