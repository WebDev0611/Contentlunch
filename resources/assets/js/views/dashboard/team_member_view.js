'use strict';

var team_member_view = Backbone.View.extend({
    tagName: "div",
    className: "dashboard-members-container",
    template: _.template(`
        <div class="dashboard-ideas-cell cell-size-5">
            <img src="<%= profile_image %>" alt="#" class="dashboard-tasks-img">
        </div>
        <div class="dashboard-members-cell cell-size-75">
            <p class="dashboard-ideas-text"><%= name %></p>
            <span class="dashboard-members-text small"><%= email %></span>
        </div>
        <div class="dashboard-members-cell cell-size-20">
            <span class="dashboard-ideas-text small" style='float:right' title='This user has <%= total_tasks %> assigned task(s)'>
                <i class="icon-checklist"></i>
                <%= total_tasks %>
            </span>
        </div>
    `),

    initialize() {
        this.render();
    },

    render() {
        this.$el.html(this.template(this.model.attributes));

        return this;
    }
});