'use strict';

var collaborator_row = Backbone.View.extend({
    tagName: 'tr',

    events: {
        'click .remove': 'removeUser',
    },

    template: _.template(`
        <td class='cell-size-5'>
            <div class="dashboard-tasks-img-wrapper">
                <img src="<%= profile_image %>" alt="#" class="dashboard-tasks-img">
            </div>
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
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        })
        .then(() => {
            this.$el.animate({ opacity: 0.5 }, 200);

            return $.ajax({
                url: '/api/account/members/' + this.model.get('id'),
                method: 'delete',
                headers: getCSRFHeader(),
            });
        })
        .then(response => this.$el.remove())
        .catch(response => {
            if (_.isObject(response)) {
                this.$el.animate({ opacity: 1.0}, 200);
                this.showErrorFeedback(response.status);
            }
        });
    },

    showErrorFeedback(status) {
        let message = 'There was an unexpected error while trying to remove this user.';

        switch (status) {
            case 404:
                message = 'The user does not belong to that account.';
                break;

            case 403:
                message = 'You cannot remove yourself from the account.';
                break;

            default:
        }

        return swal('Error', message, 'error');
    },
});