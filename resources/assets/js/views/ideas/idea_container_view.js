'use strict';

var idea_container_view = Backbone.View.extend({
    status: 'active',
    events: {},

    initialize() {
        this.listenTo(this.collection,'update',this.updated);
    },

    updated() {
        this.render(this.status);
    },

    render(status) {
        this.status = status || 'active';
        this.$el.html('');

        let active = this.collection.where({ status:this.status });

        active.forEach(function(model) {
            let element = new idea_view({ model });
            this.$el.append(element.$el);
        }.bind(this));

        return this;
    }
});