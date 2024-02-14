function previewFile(input) {
    "use strict";

    var preview = input.previousElementSibling;
    var file = input.files[0];
    var reader = new FileReader();

    if(input.files[0].size > 1000000){
        alert("Maximum file size is 1MB!");
    } else {
        reader.onloadend = function() {
            preview.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
        }
    }
}

function preview86DimensionsFile(input) {
    "use strict";

    var preview = input.previousElementSibling;
    var file = input.files[0];
    var reader = new FileReader();

    if (file.type !== 'image/png')
    {
        alert("Accepted file is png.");
        return
    }

    var img = new Image();
    img.src = window.URL.createObjectURL( file );
    img.onload = function()
    {
        var width = this.naturalWidth,
            height = this.naturalHeight;

        if (height !== 86){
            preview.src = "";
            alert("Need to height is 86!");
            return
        }

        if (width !== 86){
            preview.src = "";
            alert("Need to width is 86!");
            return;
        }

    };

    if(input.files[0].size > 1000000){
        alert("Maximum file size is 1MB!");
    }else {
        reader.onloadend = function() {
            preview.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
        }
    }
}
function preview148DimensionsFile(input) {
    "use strict";

    var preview = input.previousElementSibling;
    var file = input.files[0];
    var reader = new FileReader();

    if (file.type !== 'image/png')
    {
        alert("Accepted file is png.");
        return
    }

    var img = new Image();
    img.src = window.URL.createObjectURL( file );
    img.onload = function()
    {
        var width = this.naturalWidth,
            height = this.naturalHeight;

        if (height !== 148){
            preview.src = "";
            alert("Need to height is 148!");
            return
        }

        if (width !== 148){
            preview.src = "";
            alert("Need to width is 148!");
            return;
        }

    };

    if(input.files[0].size > 1000000){
        alert("Maximum file size is 1MB!");
    }else {
        reader.onloadend = function() {
            preview.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
        }
    }
}

function preview505540DimensionFile(input) {
    "use strict";

    var preview = input.previousElementSibling;
    var file = input.files[0];
    var reader = new FileReader();

    if (file.type === 'image/png' || file.type === 'image/jpg' || file.type === 'image/jpeg')
    {
        var img = new Image();

        img.src = window.URL.createObjectURL( file );
        img.onload = function()
        {
            var width = this.naturalWidth,
                height = this.naturalHeight;

            if (width !== 505){
                preview.src = "";
                alert("Need to width is 505!");
                return
            }

            if (height !== 540){
                preview.src = "";
                alert("Need to height is 540!");
                return;
            }

        };

        if(input.files[0].size > 1000000){
            alert("Maximum file size is 1MB!");
        }else {
            reader.onloadend = function() {
                preview.src = reader.result;
            };

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
            }
        }
    } else {
        alert("Accepted file is jpg/jpeg/png.");
        return
    }


}

function preview300343DimensionFile(input) {
    "use strict";

    var preview = input.previousElementSibling;
    var file = input.files[0];
    var reader = new FileReader();

    if (file.type === 'image/png' || file.type === 'image/jpg' || file.type === 'image/jpeg')
    {
        var img = new Image();

        img.src = window.URL.createObjectURL( file );
        img.onload = function()
        {
            var width = this.naturalWidth,
                height = this.naturalHeight;

            if (width !== 300){
                preview.src = "";
                alert("Need to width is 300!");
                return
            }

            if (height !== 343){
                preview.src = "";
                alert("Need to height is 343!");
                return;
            }

        };

        if(input.files[0].size > 1000000){
            alert("Maximum file size is 1MB!");
        }else {
            reader.onloadend = function() {
                preview.src = reader.result;
            };

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
            }
        }
    } else {
        alert("Accepted file is jpg/jpeg/png.");
        return
    }


}

function preview12041DimensionFile(input) {
    "use strict";

    var preview = input.previousElementSibling;
    var file = input.files[0];
    var reader = new FileReader();

    if (file.type === 'image/png' || file.type === 'image/jpg' || file.type === 'image/jpeg')
    {
        var img = new Image();

        img.src = window.URL.createObjectURL( file );
        console.log(window.URL.createObjectURL( file ))
        img.onload = function()
        {
            var width = this.naturalWidth,
                height = this.naturalHeight;

            if (width !== 120){
                alert("Need to width is 120!");
                return
            }

            if (height !== 41){
                preview.src = "";
                alert("Need to height is 41!");
                return;
            }

        };

        if(input.files[0].size > 1000000){
            alert("Maximum file size is 1MB!");
        }else {
            reader.onloadend = function() {
                preview.src = reader.result;
            };

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
            }
        }
    } else {
        alert("Accepted file is jpg/jpeg/png.");
        return
    }


}
