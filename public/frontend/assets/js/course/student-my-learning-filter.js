(function ($) {
    "use strict";

    $(document).on('change', ".filterBy", function () {
        var sortByID = this.value;
        var courseMyLearningRoute = $('.courseMyLearningRoute').val();
        $.ajax({
            type: "GET",
            url: courseMyLearningRoute,
            data: {"sortByID": sortByID},
            datatype: "json",
            success: function (response) {
                $('.appendCourseList').html(response)
            },
            error: function () {

            },
        });
    });
})(jQuery)
