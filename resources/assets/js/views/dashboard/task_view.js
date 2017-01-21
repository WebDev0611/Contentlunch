'use strict';

var task_view = Backbone.View.extend({
    template: _.template(`
        <div class="dashboard-tasks-container">
            <div class="dashboard-tasks-cell cell-size-5">
                <img src="<%= image %>" alt="#" class="dashboard-tasks-img">
            </div>
            <div class="dashboard-tasks-cell cell-size-80">
                <h5 class="dashboard-tasks-title">
                    <%= title %>
                </h5>
                <span class="dashboard-tasks-text">
                    <%= body %>
                </span>
                <ul class="dashboard-tasks-list">
                <% if (due !== "Invalid date") { %>
                    <li>
                        DUE DATE: <strong><%= due %></strong>
                    </li>
                <% } %>
                
                    
                    <!--
                    <li>
                        STAGE:
                        <i class="dashboard-tasks-list-icon primary icon-idea"></i>
                        <i class="dashboard-tasks-list-icon tertiary icon-content"></i>
                        <i class="dashboard-tasks-list-icon tertiary icon-alert"></i>
                        <i class="dashboard-tasks-list-icon tertiary icon-share"></i>
                    </li>
                    -->
                    <li>
                        <a href="/task/show/<%= id %>"><strong>View Task</strong></a>
                    </li>
                </ul>
            </div>
            <div class="dashboard-tasks-cell cell-size-15">
                <span class="dashboard-tasks-text small <%= active %>">
                    <%= created_at_diff.toUpperCase() %>
                </span>
            </div>
        </div>
    `),

    render() {
        this.el = this.template(this.model.attributes);
        return this.el;
    }
});