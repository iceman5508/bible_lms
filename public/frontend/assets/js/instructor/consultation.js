(function ($) {
    "use strict";
    $(document).on('click','.viewSlot', function () {
        let slotViewRoute = $(this).data('route')
        console.log()

        $.ajax({
            type: "GET",
            url: slotViewRoute,
            datatype: "json",
            success: function (response) {
                $('.appendSlotList').html(response);
            }
        });

    });

    $('.btn-close').click(function (){
        $('.appendSlotList').html('');
    })

    //Start:: Remove slot
    $(document).on("click", ".deleteTimeSlot", function (e) {
        let route = $(this).data('route');
        Swal.fire({
            title: deleteTitle,
            text: deleteText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: deleteConfirmButton
        }).then((result) => {
            if (result.value) {
                e.preventDefault();
                var div = $(this).parent('div'); //Remove field html
                $.ajax({
                    type: "DELETE",
                    url: route,
                    data:{'_token': $('meta[name="csrf-token"]').attr('content')},
                    datatype: "json",
                    success: function (response) {
                        toastr.options.positionClass = 'toast-bottom-right';
                        if (response.status == 404) {
                            toastr.error(response.msg);
                        } else {
                            div.remove();
                            toastr.success(response.msg);
                        }
                    }
                });
            } else if (result.dismiss === "cancel") {
                Swal.fire(
                    "Cancelled",
                    "Your imaginary file is safe :)",
                    "error"
                )
            }
        })
    });
    //End:: Remove slot

    $('#inlineCheckbox3').on('click',function(){
        var value = $(this).val();
        if(value==1){
            $('#consultancyArea').removeClass('d-none');
        }
    });
    $('#inlineCheckbox4').on('click',function(){
        var value = $(this).val();
        if(value==2){
            $('#consultancyArea').addClass('d-none');
        }
    });
    $('#inlineCheckbox5').on('click',function(){
        var value = $(this).val();
        if(value==3){
            $('#consultancyArea').removeClass('d-none');
        }
    });

    $('#offlineStatus').on('change',function(){
        if($(this).is(':checked') == true){
            $('#offlineMessage').removeClass('d-none');
        }else{
            $('#offlineMessage').addClass('d-none');
        }
    });

    //Start:: Onclick Day Name Change
    $('.saturdayAddSlot, .saturdayViewSlot').click(function () {
        slotdetails("Saturday", 6);
    })

    $('.sundayAddSlot, .sundayViewSlot').click(function () {
        slotdetails("Sunday", 0);
    })

    $('.mondayAddSlot, .mondayViewSlot').click(function () {
        slotdetails("Monday", 1);
    })

    $('.tuesdayAddSlot, .tuesdayViewSlot').click(function () {
        slotdetails("Tuesday", 2);
    })

    $('.wednesdayAddSlot, .wednesdayViewSlot').click(function () {
        slotdetails("Wednesday", 3);
    })

    $('.thursdayAddSlot, .thursdayViewSlot').click(function () {
        slotdetails("Thursday", 4);
    })

    $('.fridayAddSlot, .fridayViewSlot').click(function () {
        slotdetails("Friday", 5);
    })

    function slotdetails(day_name, dayCount)
    {
        $('.dayName').html(day_name)
        $('.day').val(dayCount)
    }

    //End:: Onclick Day Name Change
})(jQuery)
