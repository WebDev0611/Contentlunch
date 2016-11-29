var AvatarView = Backbone.View.extend({
    events: {
        'change #upload': 'upload'
    },

    upload: function() {
        console.log('upload triggerd');

        var form = $('form#profile_settings')[0];
        var formData = new FormData(form);
        this.fileUpload(formData).then(this.finishLoading.bind(this));
        this.startLoading();
    },

    fileUpload: function(formData) {
        return $.ajax({
            type: 'post',
            url: 'signup/photo_upload',
            data: formData,
            processData: false,
            contentType: false
        });
    },

    startLoading: function() {
        this.$el.addClass('loading');
    },

    finishLoading: function(response) {
        var image = this.$el.find('img');

        image.attr('src', response.image);
        this.$el.removeClass('loading');
    }
});