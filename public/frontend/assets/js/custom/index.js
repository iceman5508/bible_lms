(function () {
    'use strict'
    /** ============ my script ===============**/
    $(document).on("click", "a.delete", function () {
        const selector = $(this);
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
                    }
                })
            }
        })
    });

})()


