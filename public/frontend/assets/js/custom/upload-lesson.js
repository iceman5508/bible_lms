(function () {
    'use strict'
    var lecture_type = $('.lecture_type').val()
    if (lecture_type === 'youtube') {
        $('#fileDuration').removeClass('d-none');
    }
    $('.add-more-section-btn').on('click', function () {
        $('.add-more-section-wrap').removeClass('d-none');
        $('.add-more-lesson-box').addClass('d-none');
    })

    $('.cancel-add-more-section').on('click', function () {
        $('.add-more-section-wrap').addClass('d-none');
        $('.add-more-lesson-box').removeClass('d-none');
    })

    $('.lecture-type').on('click', function () {
        var type =  $(this).val();
        lectureType(type)
    })

    $('.vimeo_upload_type').change(function (){
        var vimeo_upload_type = $(this).val();
        if (vimeo_upload_type == 1) {
            $('.vimeo_Video_file_upload_div').removeClass('d-none')
            $('.vimeo_uploaded_Video_id_div').addClass('d-none')
            $('#vimeo_url_path').attr("required", true);
            $('#vimeo_url_uploaded_path').removeAttr("required");
            $('.customVimeoFileDuration').removeAttr("required");
        } else if(vimeo_upload_type == 2) {
            $('.vimeo_uploaded_Video_id_div').removeClass('d-none')
            $('.vimeo_Video_file_upload_div').addClass('d-none')
            $('#vimeo_url_path').removeAttr("required");
            $('#vimeo_url_uploaded_path').attr("required", true);
            $('.customVimeoFileDuration').attr("required", true);
        }
    })

    function lectureType(type)
    {
        if (type === 'video') {
            $('#video').removeClass('d-none');
            $('#youtube').addClass('d-none');
            $('#vimeo').addClass('d-none');
            $('#text').addClass('d-none');
            $('#imageDiv').addClass('d-none');
            $('#pdfDiv').addClass('d-none');
            $('#slide_documentDiv').addClass('d-none');
            $('#audioDiv').addClass('d-none');

            $('#fileDuration').addClass('d-none');
            $('.customFileDuration').removeAttr("required");

            $('#vimeo_upload_type').removeAttr("required");
            $('#vimeo_url_path').removeAttr("required");
            $('#vimeo_url_uploaded_path').removeAttr("required");
            $('.customVimeoFileDuration').removeAttr("required");

            $('#youtube_url_path').removeAttr("required");
            $('#video_file').attr("required", true);
            $('.textDescription').removeAttr("required");
            $('#image').removeAttr("required");
            $('#pdf').removeAttr("required");
            $('#slide_document').removeAttr("required");
            $('#audio').removeAttr("required");
        } else if (type === 'youtube') {
            $('#video').addClass('d-none');
            $('#youtube').removeClass('d-none');
            $('#vimeo').addClass('d-none');
            $('#text').addClass('d-none');
            $('#imageDiv').addClass('d-none');
            $('#pdfDiv').addClass('d-none');
            $('#slide_documentDiv').addClass('d-none');
            $('#audioDiv').addClass('d-none');

            $('#fileDuration').removeClass('d-none');
            $('.customFileDuration').attr("required", true);
            $('#youtube_url_path').attr("required", true);

            $('#vimeo_upload_type').removeAttr("required");
            $('#vimeo_url_path').removeAttr("required");
            $('#vimeo_url_uploaded_path').removeAttr("required");
            $('.customVimeoFileDuration').removeAttr("required");

            $('#video_file').removeAttr("required");
            $('.textDescription').removeAttr("required");
            $('#image').removeAttr("required");
            $('#pdf').removeAttr("required");
            $('#slide_document').removeAttr("required");
            $('#audio').removeAttr("required");
        } else if (type === 'vimeo') {
            $('#video').addClass('d-none');
            $('#youtube').addClass('d-none');
            $('#vimeo').removeClass('d-none');
            $('#text').addClass('d-none');
            $('#imageDiv').addClass('d-none');
            $('#pdfDiv').addClass('d-none');
            $('#slide_documentDiv').addClass('d-none');
            $('#audioDiv').addClass('d-none');

            $('#vimeo_upload_type').attr("required", true);
            $('#vimeo_url_path').removeAttr("required");
            $('#vimeo_url_uploaded_path').removeAttr("required");
            $('.customVimeoFileDuration').removeAttr("required");

            $('#youtube_url_path').removeAttr("required");
            $('#fileDuration').addClass('d-none');
            $('.customFileDuration').removeAttr("required");
            $('#video_file').removeAttr("required");
            $('.textDescription').removeAttr("required");
            $('#image').removeAttr("required");
            $('#pdf').removeAttr("required");
            $('#slide_document').removeAttr("required");
            $('#audio').removeAttr("required");
        } else if (type === 'text') {
            $('#video').addClass('d-none');
            $('#youtube').addClass('d-none');
            $('#vimeo').addClass('d-none');
            $('#text').removeClass('d-none');
            $('#imageDiv').addClass('d-none');
            $('#pdfDiv').addClass('d-none');
            $('#slide_documentDiv').addClass('d-none');
            $('#audioDiv').addClass('d-none');

            $('#vimeo_upload_type').removeAttr("required");
            $('#vimeo_url_path').removeAttr("required");
            $('#vimeo_url_uploaded_path').removeAttr("required");
            $('.customVimeoFileDuration').removeAttr("required");

            $('#youtube_url_path').removeAttr("required");
            $('#fileDuration').addClass('d-none');
            $('.customFileDuration').removeAttr("required");
            $('#video_file').removeAttr("required");

            $('#image').removeAttr("required");
            $('#pdf').removeAttr("required");
            $('#slide_document').removeAttr("required");
            $('#audio').removeAttr("required");
        } else if (type === 'image') {
            $('#video').addClass('d-none');
            $('#youtube').addClass('d-none');
            $('#vimeo').addClass('d-none');
            $('#text').addClass('d-none');
            $('#imageDiv').removeClass('d-none');
            $('#pdfDiv').addClass('d-none');
            $('#slide_documentDiv').addClass('d-none');
            $('#audioDiv').addClass('d-none');

            $('#vimeo_upload_type').removeAttr("required");
            $('#vimeo_url_path').removeAttr("required");
            $('#vimeo_url_uploaded_path').removeAttr("required");
            $('.customVimeoFileDuration').removeAttr("required");

            $('#youtube_url_path').removeAttr("required");
            $('#fileDuration').addClass('d-none');
            $('.customFileDuration').removeAttr("required");
            $('#video_file').removeAttr("required");
            $('.textDescription').removeAttr("required");
            $('#image').attr("required", true);
            $('#pdf').removeAttr("required");
            $('#slide_document').removeAttr("required");
            $('#audio').removeAttr("required");
        } else if (type === 'pdf') {
            $('#video').addClass('d-none');
            $('#youtube').addClass('d-none');
            $('#vimeo').addClass('d-none');
            $('#text').addClass('d-none');
            $('#imageDiv').addClass('d-none');
            $('#pdfDiv').removeClass('d-none');
            $('#slide_documentDiv').addClass('d-none');
            $('#audioDiv').addClass('d-none');

            $('#vimeo_upload_type').removeAttr("required");
            $('#vimeo_url_path').removeAttr("required");
            $('#vimeo_url_uploaded_path').removeAttr("required");
            $('.customVimeoFileDuration').removeAttr("required");

            $('#youtube_url_path').removeAttr("required");
            $('#fileDuration').addClass('d-none');
            $('.customFileDuration').removeAttr("required");
            $('#video_file').removeAttr("required");
            $('.textDescription').removeAttr("required");
            $('#image').removeAttr("required");
            $('#pdf').attr("required", true);
            $('#slide_document').removeAttr("required");
            $('#audio').removeAttr("required");
        } else if (type === 'slide_document') {
            $('#video').addClass('d-none');
            $('#youtube').addClass('d-none');
            $('#vimeo').addClass('d-none');
            $('#text').addClass('d-none');
            $('#imageDiv').addClass('d-none');
            $('#pdfDiv').addClass('d-none');
            $('#slide_documentDiv').removeClass('d-none');
            $('#audioDiv').addClass('d-none');

            $('#vimeo_upload_type').removeAttr("required");
            $('#vimeo_url_path').removeAttr("required");
            $('#vimeo_url_uploaded_path').removeAttr("required");
            $('.customVimeoFileDuration').removeAttr("required");

            $('#youtube_url_path').removeAttr("required");
            $('#fileDuration').addClass('d-none');
            $('.customFileDuration').removeAttr("required");
            $('#video_file').removeAttr("required");
            $('.textDescription').removeAttr("required");
            $('#image').removeAttr("required");
            $('#pdf').removeAttr("required");
            $('#slide_document').attr("required", true);
            $('#audio').removeAttr("required");
        } else if (type === 'audio') {
            $('#video').addClass('d-none');
            $('#youtube').addClass('d-none');
            $('#vimeo').addClass('d-none');
            $('#text').addClass('d-none');
            $('#imageDiv').addClass('d-none');
            $('#pdfDiv').addClass('d-none');
            $('#slide_documentDiv').addClass('d-none');
            $('#audioDiv').removeClass('d-none');

            $('#vimeo_upload_type').removeAttr("required");
            $('#vimeo_url_path').removeAttr("required");
            $('#vimeo_url_uploaded_path').removeAttr("required");
            $('.customVimeoFileDuration').removeAttr("required");

            $('#youtube_url_path').removeAttr("required");
            $('#fileDuration').addClass('d-none');
            $('.customFileDuration').removeAttr("required");
            $('#video_file').removeAttr("required");
            $('.textDescription').removeAttr("required");
            $('#image').removeAttr("required");
            $('#pdf').removeAttr("required");
            $('#slide_document').removeAttr("required");
            $('#audio').attr("required", true);
        }
    }

    /*** =========== Youtube validation check ===============**/
    $(function(){
        var typeYoutube = $('.oldTypeYoutube').val();
        if ( typeYoutube === 'youtube')
        {
            $('#video').addClass('d-none');
            $('#youtube').removeClass('d-none');
            $('#vimeo').addClass('d-none');

            $('#fileDuration').removeClass('d-none');
            $('.customFileDuration').attr("required", true);
            $('#youtube_url_path').attr("required", true);

            $('#vimeo_upload_type').removeAttr("required");
            $('#vimeo_url_path').removeAttr("required");
            $('#vimeo_url_uploaded_path').removeAttr("required");
            $('.customVimeoFileDuration').removeAttr("required");

            $('#video_file').removeAttr("required");
            $('#lectureTypeYoutube').attr( 'checked', true )
        }
    });

    /*** =========== video duration ===============**/
    var myVideos = [];
    window.URL = window.URL || window.webkitURL;
    document.getElementById('video_file').onchange = setFileInfo;
    function setFileInfo() {
        var files = this.files;
        myVideos.push(files[0]);
        var video = document.createElement('video');
        video.preload = 'metadata';

        video.onloadedmetadata = function() {
            window.URL.revokeObjectURL(video.src);
            var duration = video.duration;
            $('#file_duration').val(duration);
        };
        video.src = URL.createObjectURL(files[0]);
    }

    /*** =========== end video duration ===============**/

    /*** =========== vimeo video duration ===============**/
    var myVideos = [];
    window.URL = window.URL || window.webkitURL;
    document.getElementById('vimeo_url_path').onchange = setFileInfo;
    function setFileInfo() {
        var files = this.files;
        myVideos.push(files[0]);
        var video = document.createElement('video');
        video.preload = 'metadata';

        video.onloadedmetadata = function() {
            window.URL.revokeObjectURL(video.src);
            var duration = video.duration;
            $('#file_duration').val(duration);
        };
        video.src = URL.createObjectURL(files[0]);
    }

    /*** =========== end vimeo video duration ===============**/

    /*** =========== audio video duration ===============**/
    var myVideos = [];
    window.URL = window.URL || window.webkitURL;
    document.getElementById('audio').onchange = setFileInfo;
    function setFileInfo() {
        var files = this.files;
        myVideos.push(files[0]);
        var video = document.createElement('video');
        video.preload = 'metadata';

        video.onloadedmetadata = function() {
            window.URL.revokeObjectURL(video.src);
            var duration = video.duration;
            $('#file_duration').val(duration);
        };
        video.src = URL.createObjectURL(files[0]);
    }

    /*** =========== end audio video duration ===============**/

})()


