(function ($) {
    "use strict";

    $(function () {
        var course_id = $('.course_id').val();
        console.log(course_id)
        assignmentList(course_id)
    });

    $(document).on('click', '.viewAssignmentList', function () {
        var course_id = $('.course_id').val();
        assignmentList(course_id)
    });

    function assignmentList(course_id)
    {
        var studentAssignmentListRoute = $('.studentAssignmentListRoute').val();
        $.ajax({
            type: "GET",
            url: studentAssignmentListRoute,
            data: {
                'course_id': course_id,
            },
            datatype: "json",
            success: function (response) {
                $('.appendAssignment').html(response);
            },
            error: function (error) {
            },
        });
    }

    $(document).on('click', '.viewAssignmentDetails, .viewAssignmentResult, .viewAssignmentSubmit', function () {
        var course_id = $('.course_id').val();
        var assignment_id = $(this).data('assignment_id');
        var route = $(this).data('route');
        var data = {
            'course_id': course_id,
            'assignment_id': assignment_id,
        }

        $.ajax({
            type: "GET",
            url: route,
            data: data,
            datatype: "json",
            success: function (response) {

                $('.appendAssignment').html(response);
            },
            error: function (error) {

            },
        });
    });


    $(document).on('click', '.assignmentSubmitStore', function() {
        let formData = new FormData($('#assignment_file_form')[0]);
        let file = $('input[type=file]')[0].files[0];
        formData.append('file', file, file.name);
        var route = $(this).data('route');

        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            contentType: false,
            processData: false,
            cache: false,
            data: formData,
            success: function(response) {
                toastr.options.positionClass = 'toast-bottom-right';
                var errorMessages = response.messages;

                if (Array.isArray(errorMessages))
                {
                    errorMessages.forEach( message =>
                        toastr.error(message)
                    );

                    return;
                }

                if (response.status == 404) {
                    toastr.error(response.message)
                    return;
                }

                $('.appendAssignment').html(response);
                toastr.success('Assignment uploaded successfully.')

            },
            error: function(data) {
                //
            }
        });
    });
})(jQuery)
