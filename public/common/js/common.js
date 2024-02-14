

$(document).on('submit', "form.ajax", function (event) {
    event.preventDefault();
    var enctype = $(this).prop("enctype");
    if (!enctype) {
        enctype = "application/x-www-form-urlencoded";
    }
    commonAjax($(this).prop('method'), $(this).prop('action'), window[$(this).data("handler")], window[$(this).data("handler")], new FormData($(this)[0]));
});

function commonAjax(type, url, successHandler, errorHandler, data) {
    var ajaxData = {
        type: type,
        url: url,
        dataType: 'json',
        success: successHandler,
        error: errorHandler
    }
    if (typeof (data) != 'undefined') {
        ajaxData.data = data;
    }
    if (type == 'POST' || type == 'post') {
        ajaxData.encType = 'enctype';
        ajaxData.contentType = false;
        ajaxData.processData = false;
        ajaxData.data.append("ulc", localStorage.getItem("ulc"));
    }
    $.ajax(ajaxData);
}

function getShowMessage(response) {
    var output = '';
    var type = 'error';
    $('.error-message').remove();
    $('.is-invalid').removeClass('is-invalid');
    if (response['status'] == true) {
        output = output + response['message'];
        type = 'success';
        toastr.success(response.message)
        location.reload()
    } else {
        commonHandler(response)
    }
}

function commonHandler(data) {
    var output = '';
    var type = 'error';
    $('.error-message').remove();
    $('.is-invalid').removeClass('is-invalid');
    if (data['status'] == false) {
        output = output + data['message'];
    } else if (data['status'] === 422) {
        var errors = data['responseJSON']['errors'];
        output = getValidationError(errors);
    } else if (typeof data['responseJSON']['error'] !== 'undefined') {
        output = data['responseJSON']['error'];
    } else {
        output = data['responseJSON']['message'];
    }
    alertAjaxMessage(type, output);
}

function alertAjaxMessage(type, message) {
    if (type === 'success') {
        toastr.success(message);
    } else if (type === 'error') {
        toastr.error(message);
    } else if (type === 'warning') {
        toastr.error(message);
    } else {
        return false;
    }
}

function getValidationError(errors) {
    var output = 'Validation Errors';
    $.each(errors, function (index, items) {
        if (index.indexOf('.') != -1) {
            var name = index.split('.');
            var getName = name.slice(0, -1).join('-');
            var i = name.slice(-1);
            var itemSelect = $(document).find('.' + getName + ':eq(' + i + ')')
            itemSelect.addClass('is-invalid');
            itemSelect.closest('div').append('<span class="text-danger p-2 error-message">' + items[0] + '</span>')
        } else {
            var itemSelect = $("[name='" + index + "']");
            itemSelect.addClass('is-invalid');
            itemSelect.closest('div').append('<span class="text-danger p-2 error-message">' + items[0] + '</span>')
        }
    });
    return output;
}

$(document).on("submit", "form", function (e) {
    var form = $(this);
    form.find('button[type=submit]').attr('disabled', true);
    setTimeout(function () {
        form.find('button[type=submit]').attr('disabled', false);
    }, 5000)
})
