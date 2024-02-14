(function () {
    'use strict'

    $(document).on("click", "a.deleteBtn", function () {
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
                        if(data.status == true){
                            selector.closest('.removable-item').fadeOut('fast');
                        }
                        Swal.fire({
                            title: 'Deleted',
                            html: ' <span style="color:red">'+data.message+'</span> ',
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

})()

