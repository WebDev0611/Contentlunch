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
});