$('#contentType').on('change', function (e) {
    showCustomContentType();
});

function showCustomContentType() {
    let text = $("#contentType").find("option:selected").text();
    if(text.toLowerCase() === 'custom') {
        $('#customContentType').parent().removeClass('hidden');
        $('input[name="custom_content_type_present"]').val('true');
    } else {
        $('#customContentType').parent().addClass('hidden');
        $('input[name="custom_content_type_present"]').val('false');
    }
}

$(function() {
    showCustomContentType();
});