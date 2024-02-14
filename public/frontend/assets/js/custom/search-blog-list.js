(function () {
    'use strict'
    $(document).keyup('.searchBlogBar',function() {
        var title = $('.searchBlog').val()
        var searchBlogRoute = $('.searchBlogRoute').val()
        console.log(title)

        if (title) {
            $('.searchBlogBox').removeClass('d-none')
            $('.searchBlogBox').addClass('d-block')
        } else {
            $('.searchBlogBox').removeClass('d-block')
            $('.searchBlogBox').addClass('d-none')
        }

        $.ajax({
            type: "GET",
            url: searchBlogRoute,
            data: {'title': title},
            success: function (response) {
                $('.appendBlogSearchList').html(response);
            }
        });
    });
})()
