(function() {

    var imageUploader = new Dropzone('#image-uploader', { url: '/edit/images' });
    var attachmentUploader = new Dropzone('#attachment-uploader', { url: '/edit/attachments' });

    imageUploader.on('success', function(file, response) {
        var hiddenField = $('<input/>', {
            name: 'images[]',
            type: 'hidden',
            value: response.file
        });

        hiddenField.appendTo($('form'));
    });

    attachmentUploader.on('success', function(file, response) {
        var hiddenField = $('<input/>', {
            name: 'files[]',
            type: 'hidden',
            value: response.file
        });

        hiddenField.appendTo($('form'));
    });

})();