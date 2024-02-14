(function ($) {
    "use strict";
    $(document).ready(function () {
        $('#coupon_type').on('change', function () {
            var type = this.value;

            if (type == 1) {
                $('#instructor_id_div').removeClass('d-block');
                $('#course_id_div').removeClass('d-block');

                $('#instructor_id_div').addClass('d-none');
                $('#course_id_div').addClass('d-none');

                $('#instructor_ids').removeAttr('required');
                $('#course_ids').removeAttr('required');
            }
            if (type == 2) {
                $('#instructor_id_div').removeClass('d-none');
                $('#instructor_id_div').addClass('d-block');
                $('#course_id_div').removeClass('d-block');
                $('#course_id_div').addClass('d-none');

                $('#instructor_ids').attr('required', true)
                $('#course_ids').removeAttr('required');
            }

            if (type == 3) {
                $('#course_id_div').removeClass('d-none');
                $('#course_id_div').addClass('d-block');
                $('#instructor_id_div').removeClass('d-block');
                $('#instructor_id_div').addClass('d-none');

                $('#course_ids').attr('required', true)
                $('#instructor_ids').removeAttr('required');
            }
        })
    });

})(jQuery)
