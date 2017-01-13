var CharacterCounterView = Backbone.View.extend({
    characters: 0,

    initialize() {
        this.$el.hide();
        this.render();
    },

    render() {
        this.$el.find('.count').text(this.characters);

        if (this.characters > 140) {
            this.invalidCount();
        } else {
            this.validCount();
        }

        return this;
    },

    invalidCount() {
        if (!this.$el.hasClass('invalid-count')) {
            this.$el.addClass('invalid-count');
        }
    },

    validCount() {
        if (this.$el.hasClass('invalid-count')) {
            this.$el.removeClass('invalid-count');
        }
    },

    show() {
        this.$el.slideDown('fast');
    },

    hide() {
        this.$el.slideUp('fast');
    },

    update(content) {
        if (this.isTweet()) {
            let html = content;
            let div = document.createElement("div");
            div.innerHTML = html;
            let text = div.textContent || div.innerText || "";

            this.characters = text.length;
            this.render();
        }
    },

    isTweet() {
        const TWEET = '3';
        const selectedContentType = $('#contentType').length ? $('#contentType').val() : 0;

        return selectedContentType == TWEET;
    }
});