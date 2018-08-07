'use strict';

var dashboard_activity_item_view = Backbone.View.extend({
    tagName: "div",
    className: "plan-activity-box-container",
    template: _.template(`
        <div class="plan-activity-box-img">
            <img src="<%= image %>" alt="#">
        </div>
        <div class="plan-activity-box">
            <span class="plan-activity-title">
                <a href="#"><%= who %></a> <%= action %>
                <a href="#"> <%= title %></a> on
                <a href="#"><%= content %></a>
            </span>
            <p class="plan-activity-text">
                <%= body %>
            </p>
        </div>
    `),

    initialize() {
        this.$el.append(this.template(this.model.attributes));
    }
});