(function ($) {
    "use strict";

    /** ============ my script ===============**/
    $(document).on("click", "a.delete", function () {
        const selector = $(this);
        const isReload = $(this).data("reload");
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
                $.ajax({
                    type: 'GET',
                    url: $(this).data("url"),
                    success: function (data) {
                        selector.closest('.removable-item').fadeOut('fast');
                        Swal.fire({
                            title: 'Deleted',
                            html: ' <span style="color:red">'+deleteSuccessText+'</span> ',
                            timer: 2000,
                            icon: 'success'
                        })

                        if(typeof isReload != 'undefined'){
                            location.reload();
                        }
                    }
                })
            }
        })
    });

    $(document).on("click", ".deleteItem", function () {
        let form_id = this.dataset.formid;
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
                $("#" + form_id).submit();
            } else if (result.dismiss === "cancel") {
                Swal.fire(
                    "Cancelled",
                    "Your imaginary file is safe :)",
                    "error"
                )
            }
        })
    });

    $(document).on("click", "a.paid-order", function (e) {
        e.preventDefault()
        const selector = $(this);
        Swal.fire({
            title: 'Sure! You want to Paid this?',
            text: "This Order will be Paid!",
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Paid It!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'GET',
                    url: $(this).data("url"),
                    success: function (data) {
                        selector.closest('.removable-item').fadeOut('fast');
                        Swal.fire({
                            title: 'Paid',
                            html: ' <span style="color:green">This Order has been paid</span> ',
                            timer: 2000,
                            icon: 'success'
                        })
                    }
                })
            }
        })
    });

    $(document).on("click", "a.cancelled-order", function (e) {
        e.preventDefault()
        const selector = $(this);
        Swal.fire({
            title: 'Sure! You want to Cancelled this?',
            text: "This Order will be cancelled!",
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Cancelled It!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'GET',
                    url: $(this).data("url"),
                    success: function (data) {
                        selector.closest('.removable-item').fadeOut('fast');
                        Swal.fire({
                            title: 'Paid',
                            html: ' <span style="color:green">This Order has been Cancelled</span> ',
                            timer: 2000,
                            icon: 'error'
                        })
                    }
                })
            }
        })
    });

    $(document).ready(function () {
        $(".multiple-basic-single").select2({
            placeholder: "Select Option",
        });
        $(".multiple-select-input").select2({
            tags: true,
            tokenSeparators: [',', ' '],
        })
    });
})(jQuery)
