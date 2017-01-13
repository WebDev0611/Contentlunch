'use strict';

var dashboard_activity_feed_view = Backbone.View.extend({
    initialize() {
        this.render();
    },

    render() {
        this.collection.each(function(model) {
            let activity_item = new activity_item_view({ model: model });
            this.$el.append(activity_item.$el);
        }.bind(this));

        return this;
    }
});