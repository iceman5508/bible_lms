$(document).ready(function () {
    'use strict'
    $(".note").on("click", function () {
        $("#withdrawalModal #uuid").val($(this).data("uuid"));
        $("#withdrawalModal #status").val($(this).data("status"));
        $("#withdrawalModal").modal("show");
    });

    $(".view-note").on("click", function () {
        $("#rejectedNoteModal .note").text($(this).data("note"));
        $("#rejectedNoteModal").modal("show");
    });

});
