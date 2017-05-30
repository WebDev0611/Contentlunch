let contentTypeSelector = null;

if($('#contentType').length !== 0) {
    contentTypeSelector = $('#contentType');
} else if ($('#content-type-id').length !== 0) {
    contentTypeSelector = $('#content-type-id');
}

if(contentTypeSelector !== null) {
    contentTypeSelector.on('change', function (e) {
        showCustomContentType();
    });

    function showCustomContentType() {
        let text = contentTypeSelector.find("option:selected").text();
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
}
