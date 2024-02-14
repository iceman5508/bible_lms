"use strict";

function slugable() {
    var title = $('.slugable').val().toLowerCase();
    var BaseTitle = title.replace("/[~`{}.'\"\!\@\#\$\%\^\&\*\(\)\_\=\+\/\?\>\<\,\[\]\:\;\|\\\]/", '').replace(/[_\s]/g, '-') // Replace space into '-'
    $(".slug").val(BaseTitle);
}

function getMyself() {
    var opt_url = $('.slug').val().toLowerCase();
    var Url = opt_url.replace("/[~`{}.'\"\!\@\#\$\%\^\&\*\(\)\_\=\+\/\?\>\<\,\[\]\:\;\|\\\]/", '').replace(/[_\s]/g, '-'); // Replace space into '-'
    $(".slug").val(Url);
}
