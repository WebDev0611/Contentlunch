(function() {

    var AvatarView = Backbone.View.extend({
        events: {
            'change #upload': 'upload'
        },

        upload: function() {
            var form = $('form')[0];
            var formData = new FormData(form);
            fileUpload(formData)
                .then(this.finishLoading.bind(this));
            this.startLoading();
        },

        startLoading: function() {
            this.$el.addClass('loading');
        },

        finishLoading: function(response) {
            var image = this.$el.find('img');

            image.attr('src', response.image);
            this.$el.removeClass('loading');
        }
    })

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