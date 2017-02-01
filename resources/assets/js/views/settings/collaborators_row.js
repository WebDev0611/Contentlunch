'use strict';

var collaborator_row = Backbone.View.extend({
    tagName: 'tr',

    events: {
        'click .remove': 'removeUser',
    },

    template: _.template(`
        <td class='cell-size-5'>
            <img src="<%= profile_image %>" alt="#" class="dashboard-tasks-img">
        </td>
        <td>
            <p class='title'><%= name %></p>
        </td>
        <td>
            <%= location %>
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
                        <a href="#" class='remove'>Remove User</a>
                    </li>
                </ul>
            </div>
        </td>
    `),

    render() {
        let template = this.template(this.model.attributes.toJSON());
        this.$el.html(template);

        return this;
    },

    removeUser() {
        console.log('piroca');
        let id = this.model.get('id');
        console.log('id: ' + id);
    },
});