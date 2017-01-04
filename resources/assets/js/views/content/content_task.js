'use strict';

var ContentTaskView = Backbone.View.extend({
    template: _.template(`
        <div class="task">
            <div class="body">
                <div class="checkcircle">
                    <i class="icon-check-light"></i>
                </div>

                <div class="user-avatar">
                    <img src="<%= user.profile_image %>" alt="<%= user.name %>" title="<%= user.name %>">
                </div>

                <p class="title"><%= name %></p>
                <p><%= due_date %></p>
            </div>

            <div class="task-actions">
                <ul class="list-inline list-actions">
                    <li><a href="#task-<%= id %>" data-toggle="collapse"><i class="icon-edit-pencil"></i></a></li>
                    <li><a href="#"><i class="icon-trash"></i></a></li>
                </ul>
            </div>
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