(function () {
    'use strict'

    $("#country_id").on("change", function () {
        var country_id = $(this).val();
        var base_url = $("#base_url").val();

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
            url: base_url + "/admin/instructor/get-state-by-country/" + country_id,
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
            url: base_url + "/admin/instructor/get-city-by-state/" + state_id,
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

})()

