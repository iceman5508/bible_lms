$("[data-background]").each(function () {
    $(this).css(
        "background-image",
        "url(" + $(this).attr("data-background") + ")"
    );
});