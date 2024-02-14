"use strict";

$("#country_id").on("change", function () {
    var country_id = $(this).val();
    var base_url = $("#base_url").val();
    console.log(base_url)
    $('#state_id').empty().append($('<option>', {
        value: '',
        text: 'Select State'
    }));
    $('#city_id').empty().append($('<option>', {
        value: '',
        text: 'Select City'
    }));
    $.ajax({
        type: "GET",
        url: base_url + "/student/get-state-by-country/" + country_id,
        success: function (GetData) {
            $.each($.parseJSON(GetData), function (i, obj) {
                $('#state_id').append($('<option>', {
                    value: obj.id,
                    text: obj.name
                }));
            });
        }
    });
});

$("#state_id").on("change", function () {
    var state_id = $(this).val();
    var base_url = $("#base_url").val();
    $('#city_id').empty();
    $.ajax({
        type: "GET",
        url: base_url + "/student/get-city-by-state/" + state_id,
        success: function (GetData) {
            $.each($.parseJSON(GetData), function (i, obj) {
                $('#city_id').append($('<option>', {
                    value: obj.id,
                    text: obj.name
                }));
            });
        }
    });
});




$('.add-more-certificate').on('click',function () {
    $(this).parents('.certificates').children('.certificate-item').append(`<div class="row mb-30 removable-item">
                    <div class="col-md-8">
                        <label class="font-medium font-15 color-heading">Title of the certificate</label>
                        <input type="text" name="certificate_title[]" class="form-control" placeholder="Title of the certificate">
                    </div>
                    <div class="col-md-3">
                        <label class="font-medium font-15 color-heading">Date</label>
                        <input type="text" name="certificate_date[]" class="form-control" placeholder="Date">
                    </div>
                    <div class="col-md-1">
                        <div class="mt-45">
                            <a href="javascript:void(0);" class="remove-item"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                </div>`);
});

$('.add-more-award').on('click',function () {
    $(this).parents('.awards').children('.award-item').append(`<div class="instructor-add-extra-field-box mb-30 removable-item">
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="font-medium font-15 color-heading">Title of the award</label>
                                    <input type="text" name="award_title[]" class="form-control" placeholder="Title of the award">
                                </div>

                                <div class="col-md-3">
                                    <label class="font-medium font-15 color-heading">Year</label>
                                    <input type="text" name="award_year[]" class="form-control" placeholder="Year">
                                </div>
                                <div class="col-md-1">
                                    <div class="mt-45">
                                        <a href="javascript:void(0);" class="remove-item"><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>`);
});

// ========== remove item =========
$(document).on('click','.remove-item',function () {
    $(this).parents('.removable-item').remove();
});
