(function ($) {
    "use strict";
    $(".set-uuid").on("click", function (){
        // $(".certificate-list").css("background", "#fff");
        $(".certificate-list").removeClass("certificate-selected");
        var uuid = $(this).data("id");
        // $("#selected-"+uuid).css("background", "#d1d1d1");
        $("#selected-"+uuid).addClass("certificate-selected");
        $(".certificate-uuid").val(uuid);
    });
})(jQuery)
