(function() {

    $('#upload').change(function() {
        var form = $('form')[0];
        var formData = new FormData(form);
        fileUpload(formData);
    })

    function fileUpload(formData) {
        $.ajax({
            type: 'post',
            url: 'signup/photo_upload',
            data: formData,
            success: function (data) {
                console.log(data);
            },
            processData: false,
            contentType: false
        });
    }

})();