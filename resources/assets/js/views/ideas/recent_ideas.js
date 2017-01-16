'use strict';

var recent_ideas_view = Backbone.View.extend({
    idea_views: [],

    initialize() {
        this.listenTo(this.collection, 'update', this.render);
        this.render();
    },

    render() {
        if (this.collection.length > 0) {
            this.appendToIdeaViews();
        } else {
            this.appendCreateIdeaButton();
        }

        this.appendIdeas();

        return this;
    },

    appendCreateIdeaButton() {
        let createButtonTemplate = `
            <div class="dashboard-ideas-container idea-empty">
                <div class="dashboard-ideas-cell">
                    0 Ideas: <a href="/plan">Create One</a>
                </div>
            </div>
        `;
        this.$el.append($(createButtonTemplate));
    },

    appendToIdeaViews() {
        this.$el.find('.idea-empty').remove();
        this.collection.each((model) => this.idea_views.push(new recent_idea_view({ model: model })));
    },

    appendIdeas() {
        this.idea_views.forEach((view) => {
            view.$el.hide();
            view.$el.fadeIn();
            this.$el.append(view.el);
        });
    }
});
