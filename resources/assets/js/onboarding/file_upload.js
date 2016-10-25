(function() {

    var view = new AvatarView({ el: '#signup-onboarding-avatar' });

    function fileUpload(formData) {
        return $.ajax({
            type: 'post',
            url: 'signup/photo_upload',
            data: formData,
            processData: false,
            contentType: false
        });
    }

})();