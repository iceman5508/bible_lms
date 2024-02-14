(function ($) {
    "use strict";

    $(document).on('click', '.updateMark', function (e) {
        e.preventDefault();
        const modal = $('#assignmentEditModal');
        modal.find('.user_name').html($(this).data('item').user.name)
        modal.find('.user_email').html($(this).data('item').user.email)
        modal.find('input[name=marks]').val($(this).data('item').marks)
        modal.find('textarea[name=notes]').val($(this).data('item').notes)
        let route = $(this).data('updateroute');
        $('#updateEditModal').attr("action", route)
        modal.modal('show')
    })

    $(document).on('click', '.downloadAssignment', function (e) {
        var id = $(this).data('id')
        var downloadAssignmentRoute = $('.downloadAssignmentRoute').val();

        $.ajax({
            type: "GET",
            data:{'id': id},
            url: downloadAssignmentRoute,
            success: function (response) {
                if (response.data.status == 404)
                {
                    toastr.options.positionClass = 'toast-bottom-right';
                    toastr.error('warning', response.data.msg)
                }
            }
        });
    });
})(jQuery)
