(function () {
    'use strict'

    $("#type").on("change", function (){
        var type = $(this).val();
        if (type === 'ebook')
        {
            $("#ebook").removeClass('d-none');
            $(".main_file").prop('required',true);
        } else  {
            $("#ebook").addClass('d-none');
            $(".main_file").prop('required',false);
        }
    });

})()
