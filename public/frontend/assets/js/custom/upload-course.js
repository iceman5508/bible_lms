(function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')
    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })


    $('.select2').select2({
        width: '100%'
    });

    $('.select2-search__field').css('width', '10%');



    $("#category_id").on("change", function () {
        var category_id = $(this).val();
        var base_url = $("#base_url").val();
        $('#subcategory_id').empty().append($('<option>', {
            value: '',
            text: 'Select sub category'
        }));

        $.ajax({
            type: "GET",
            url: base_url + "/instructor/course/get-subcategory-by-category/" + category_id,
            success: function (GetData) {
                $.each($.parseJSON(GetData), function (i, obj) {
                    $('#subcategory_id').append($('<option>', {
                        value: obj.id,
                        text: obj.name
                    }));
                });
            }
        });
    });

    // Learner accesibility for free and paid course
    $('.learner_accessibility').on('change', function () {
        let learner_accessibility = $(this).val();
        if (learner_accessibility == 'paid')
        {
            $('.priceDiv').removeClass('d-none')
            $('.priceDiv').addClass('d-block')
            $('.price').attr("required", true);
            $('.old_price').attr("required", true);
        } else if(learner_accessibility == 'free') {
            $('.priceDiv').removeClass('d-block')
            $('.priceDiv').addClass('d-none')
            $('.price').removeAttr('required')
            $('.old_price').removeAttr('required')
        }
    })

    $(function (){
        let learner_accessibility = $('.learner_accessibility').val()
        if (learner_accessibility == 'paid')
        {
            $('.priceDiv').removeClass('d-none')
            $('.priceDiv').addClass('d-block')
            $('.price').attr("required", true);
            $('.old_price').attr("required", true);
        } else if(learner_accessibility == 'free') {
            $('.priceDiv').removeClass('d-block')
            $('.priceDiv').addClass('d-none')
            $('.price').removeAttr('required')
            $('.old_price').removeAttr('required')
        }
    });


    // Upload Video
    $('#upload-course-video-1 .theme-button1').on('click', function () {
        $('#upload-course-video-1').addClass('d-none')
        $('#upload-course-video-2').removeClass('d-none')
    })

    $('#upload-course-video-2 .theme-button1').on('click', function () {
        $('#upload-course-video-2').addClass('d-none')
        $('#upload-course-video-3').removeClass('d-none')
    })
    $('#upload-course-video-2 .common-upload-lesson-btn').on('click', function () {
        $('#upload-course-video-2').addClass('d-none')
        $('#upload-course-video-4').removeClass('d-none')
    })
    $('#upload-course-video-3 .common-upload-lesson-btn').on('click', function () {
        $('#upload-course-video-3').addClass('d-none')
        $('#upload-course-video-4').removeClass('d-none')
    })

    $('#upload-course-video-4 .common-upload-video-btn').on('click', function () {
        $('#upload-course-video-5').removeClass('d-none')
    })

    $('#upload-course-video-5 .theme-button1').on('click', function () {
        $('#upload-course-video-5').addClass('d-none')
        $('#upload-course-video-4').addClass('d-none')
        $('#upload-course-video-6').removeClass('d-none')
        $('#upload-course-video-6').addClass('show-next-go-btn')
    })

    $('#upload-course-video-6 .upload-course-video-main-edit-btn').on('click', function () {
        $('#upload-course-video-6').addClass('d-none')
        $('#upload-course-video-7').removeClass('d-none')
    })
    $('#upload-course-video-7 .upload-video-processing-item-update-btn.theme-button1').on('click', function () {
        $('#upload-course-video-7').addClass('d-none')
        $('#upload-course-video-6').removeClass('d-none')
        $('#upload-course-video-6').removeClass('show-next-go-btn')
    })

    // Add More Section Show/Hide Option Start
    $('.add-more-section-btn').on('click', function () {
        $('.add-more-section-wrap').removeClass('d-none')
    })
    // Add More Section Show/Hide Option End

    // Onclick Add or Remove Class from Stepper Form End

})()


