'use strict';

var team_member_view = Backbone.View.extend({
    tagName: "div",
    className: "dashboard-members-container",
    template: _.template(`
        <div class="dashboard-ideas-cell">
            <img src="<%= image %>" alt="#" class="dashboard-tasks-img">
        </div>
        <div class="dashboard-members-cell">
            <p class="dashboard-ideas-text"><%= name %></p>
            <span class="dashboard-members-text small"><%= email %></span>
        </div>
        <div class="dashboard-members-cell">
            <span class="dashboard-ideas-text small">
                <i class="icon-checklist"></i>
                <%= tasks %>
            </span>
        </div>
    `),

    initialize() {
        this.$el.append( this.template(this.model.attributes) );
    },

    render() {
        return this;
    }
});