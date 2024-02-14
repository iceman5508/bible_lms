(function ($) {
    "use strict";

    $(document).on('click', ".copyZoomUrl", function () {
        var copyUrl = $(this).data('url');
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(copyUrl).select();
        document.execCommand("copy");
        $temp.remove();

        toastr.options.positionClass = 'toast-bottom-right';
        toastr.success('Copied URL');
    })
})(jQuery)
