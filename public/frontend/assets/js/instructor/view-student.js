(function ($) {
    "use strict";
    $('.viewStudent').on('click', function(e){
        e.preventDefault();
        const modal = $('.viewStudentModal');
        modal.find('.course_name').text($(this).data('item').course.title);
        modal.find('.phone').text($(this).data('item').user.phone_number);
        modal.find('.user_name').text($(this).data('item').user.name);
        modal.find('.email').text($(this).data('item').user.email);
        modal.find('.country').text($(this).data('country'));
        modal.find('.purchase_date').text($(this).data('purchase_date'));
        modal.find($('.user_image')).attr('src', $(this).data('image'))
    })
})(jQuery)
