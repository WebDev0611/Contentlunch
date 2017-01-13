var CharacterCounterView = Backbone.View.extend({
    characters: 0,

    initialize: function() {
        this.$el.hide();
        this.render();
    },

    render: function() {
        this.$el.find('.count').text(this.characters);

        if (this.characters > 140) {
            this.invalidCount();
        } else {
            this.validCount();
        }

        return this;
    },

    invalidCount: function() {
        if (!this.$el.hasClass('invalid-count')) {
            this.$el.addClass('invalid-count');
        }
    },

    validCount: function() {
        if (this.$el.hasClass('invalid-count')) {
            this.$el.removeClass('invalid-count');
        }
    },

    show: function() {
        this.$el.slideDown('fast');
    },

    hide: function() {
        this.$el.slideUp('fast');
    },

    update: function(content) {
        if (this.isTweet()) {
            var html = content;
            var div = document.createElement("div");
            div.innerHTML = html;
            var text = div.textContent || div.innerText || "";

            this.characters = text.length;
            this.render();
        }
    },

    isTweet: function() {
        var TWEET = '3';
        var selectedContentType = $('#contentType').length ? $('#contentType').val() : 0;

        return selectedContentType == TWEET;
    }
});