'use strict';

var dashboard_campaign_view = Backbone.View.extend({
    template: _.template(`
        <div class="dashboard-tasks-container">
            <div class="dashboard-tasks-cell">
                <img src="<%= image %>" alt="#" class="dashboard-tasks-img">
            </div>
            <div class="dashboard-tasks-cell">
                <h5 class="dashboard-tasks-title">
                    <%= title %>
                </h5>
                <ul class="dashboard-tasks-list">
                    <% if (due !== "Invalid date") { %>
                        <li>
                            DUE DATE: <strong><%= due %></strong>
                        </li>
                    <% } %>
                </ul>
            </div>
            <div class="dashboard-tasks-cell">
               SOMETHING
            </div>
        </div>
    `),

    render() {
        this.el = this.template(this.model.attributes);
        return this.el;
    }
});
