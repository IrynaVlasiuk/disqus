function ajaxHandler(type, url, data, success, error) {
    $.ajax({
        type: type,
        url: url,
        data: data,
        success: success,
        error: error
    })
}
