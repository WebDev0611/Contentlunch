'use strict';

var collaborator_row = Backbone.View.extend({
    template: _.template(`
        <td>
            <img src="<%= profile_image %>" alt="#" class="dashboard-tasks-img">
            <p class='title'><%= name %></p>
        </td>
        <td class="tbl-right">
            <div class="actionbtnbox">
                <button
                    type="button"
                    class="button button-action"
                    data-toggle="dropdown">

                <i class="icon-add-circle"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                        <a href="#">Remove User</a>
                    </li>
                </ul>
            </div>
        </td>
    `),

    tagName: 'tr',

    render: function() {
        let template = this.template(this.model.attributes.toJSON());
        this.$el.html(template);

        return this;
    },
});