(function ($) {
    "use strict";

    //Normal Video
    $('.myVideo').on('ended', function (){
        callCompleteCourse();
    })

    // Vimeo video
    $(document).ready(function(){

        var vimeoVideoSource = $('.vimeoVideoSource').val();
        if (vimeoVideoSource) {
            var iframe = $('#playerVideoVimeo iframe');
            var player = new Vimeo.Player(iframe);

            player.on('ended', function() {
                callCompleteCourse();
            });
        }

    });

})(jQuery)

function callCompleteCourse(){
    $.ajax({
        type: "GET",
        url: videoCompletedRoute,
        data: {'course_id': course_id, 'lecture_id': lecture_id,  'enrollment_id': enrollment_id},
        datatype: "json",
        success: function (response) {
            if(typeof response.data !== 'undefined' && response.data !== null && typeof response.data.html !== 'undefined' && response.data.html !== null ){
                $('#demo-certificate').html(response.data.html).promise().then(function(){
                    saveToServer(response.data.certificate_number);
                });
            }
            else{
                toastr.options.positionClass = 'toast-bottom-right';
                if (nextLectureRoute) {
                    window.location.href = nextLectureRoute;
                } else {
                   location.reload();
                }

            }
        },
        error: function (error) {

        },
    });
}

function saveToServer(certificate_number){
    html2canvas(document.getElementById("certificate-preview-div-hidden")).then(function(canvas){
        var dataURL = canvas.toDataURL();
        $.ajax({
            type: "POST",
            url: certificateSaveRoute,
            data: {'certificate_number' : certificate_number, 'course_id': course_id, 'lecture_id': lecture_id,  'enrollment_id': enrollment_id, 'file': dataURL , '_token': $('meta[name="csrf-token"]').attr('content')},
            success: function (response) {
                if(response.status == 200){
                    location.reload();
                }
            }
        });
    });
}