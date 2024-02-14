(function ($) {
    "use strict";

    $(function (){
        var course_id = $('.firstCourseId').val();
        getCourseDiscussionList(course_id);
    });


    $(document).on('click', '.courseId', function () {
        var course_id = $(this).data('course_id');
        getCourseDiscussionList(course_id);
    });


    function getCourseDiscussionList(course_id)
    {
        var courseDiscussionListRoute = $('.courseDiscussionListRoute').val();

        $.ajax({
            type: "GET",
            url: courseDiscussionListRoute,
            data: {
                'course_id': course_id,
            },
            datatype: "json",
            success: function (response) {
                $('.appendDiscussionList').html(response);
            },
            error: function (error) {
            },
        });
    }

    $(document).keyup('.search_course_title', function () {
        var search_title = $('.search_course_title').val();
        var discussionIndexRoute = $('.discussionIndexRoute').val();

        $.ajax({
            type: "GET",
            url: discussionIndexRoute,
            data: {
                'search_title': search_title,
            },
            datatype: "json",
            success: function (response) {
                $('.appendDiscussionCourseList').html(response);
            },
            error: function (error) {
            },
        });
    });

    $(document).on('click', '.instructorDiscussionReply', function () {
        var discussion_id = $(this).data('discussion_id');
        var commentReply = $('.commentReply_'+ discussion_id).val();
        var route = $(this).data('route');
        var course_id = $(this).data('course_id');

        $.ajax({
            type: "POST",
            url: route,
            data: {'course_id': course_id, 'commentReply': commentReply, '_token': $('meta[name="csrf-token"]').attr('content')},
            datatype: "json",
            success: function (response) {
                toastr.options.positionClass = 'toast-bottom-right';
                toastr.success('Replied successful.')
                $('.appendDiscussionList').html(response);
            },
            error: function (error) {
            },
        });
    });
})(jQuery)
