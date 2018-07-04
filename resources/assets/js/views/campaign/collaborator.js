'use strict';

var CampaignCollaboratorView = Backbone.View.extend({
    template: _.template(`
        <a href="#">
            <div class="user-avatar">
                <img src="<%= profile_image %>" alt="#">
            </div>
            <p class="title"><%= name %></p>
            <p class="email"><%= email %></p>
        </a>
    `),

    tagName: 'li',

    render() {
        this.$el.html(this.template(this.model.attributes.toJSON()));

        return this;
    }
});