'use strict';

var ContentTaskView = Backbone.View.extend({
    events: {
        'click .task-remove': 'removeTask',
        'click .checkcircle': 'toggleTask',
    },

    template: _.template(`
        <div class="task">
            <div class="body">
                <div class="checkcircle pointer">
                    <i class="icon-check-light"></i>
                </div>

                <div class="user-avatar">
                <% if (typeof(user.profile_image) !== "undefined" && user.profile_image !== null ) { %>
                    <img src="<%= user.profile_image %>" alt="<%= user.name %>" title="<%= user.name %>">
                <% } else { %>
                    <img src="/images/cl-avatar2.png" alt="<%= user.name %>" title="<%= user.name %>">
                <% } %>
                </div>

                <p class="title">
                    <a href="/task/show/<%= id %>">
                        <%= name %>
                    </a>
                </p>

                <p><%= due_date_diff %></p>
            </div>

            <div class="task-actions">
                <ul class="list-inline list-actions">
                    <li><a class='task-edit' target="_blank" href="/task/show/<%= id %>"><i class="icon-edit-pencil"></i></a></li>
                    <li><a class='task-remove' href="#"><i class="icon-trash"></i></a></li>
                </ul>
            </div>

            <div class="task-content collapse" id="task-<%= id %>">
                <div class="form-group">
                    <fieldset class="form-fieldset clearfix">
                        <legend class="form-legend">Assigned</legend>
                        <ul class="images-list pull-left">
                            <li>
                                <img src="/images/cl-avatar2.png" alt="#">
                            </li>
                            <li>
                                <img src="/images/cl-avatar2.png" alt="#">
                            </li>
                            <li>
                                <img src="/images/cl-avatar2.png" alt="#">
                            </li>
                        </ul>
                        <div class="dropdown pull-right">
                            <button type="button" class="button button-action large"
                                    data-toggle="dropdown">
                                <i class="icon-add-circle"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li class="dropdown-header-secondary">
                                    <span class="dropdown-header-secondary-text">
                                        Select team member
                                    </span>
                                    <button class="button button-micro pull-right text-uppercase">
                                        Submit
                                    </button>
                                </li>
                                <li>
                                    <input type="text" class="dropdown-header-secondary-search"
                                           placeholder="Team Member Name">
                                </li>
                                <li>
                                    <label for="Friend" class="checkbox-image">
                                        <input id="Friend" type="checkbox">
                                        <span>
                                            <img src="/images/cl-avatar2.png" alt="#">
                                        </span>
                                    </label>
                                    <label for="Friend" class="checkbox-image">
                                        <input id="Friend" type="checkbox">
                                        <span>
                                            <img src="/images/cl-avatar2.png" alt="#">
                                        </span>
                                    </label>
                                    <label for="Friend" class="checkbox-image">
                                        <input id="Friend" type="checkbox">
                                        <span>
                                            <img src="/images/cl-avatar2.png" alt="#">
                                        </span>
                                    </label>
                                    <label for="Friend" class="checkbox-image">
                                        <input id="Friend" type="checkbox">
                                        <span>
                                            <img src="/images/cl-avatar2.png" alt="#">
                                        </span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </fieldset>
                </div>

                <div class="input-form-group">
                    <label for="#">Deadline</label>
                    <div class="form-suffix">
                        <i class="icon-calendar picto"></i>
                        <input type="text" class="input" placeholder="Due date and time">
                    </div>
                </div>

                <div class="button-bar">
                    <button type="button" class="button button-small button-outline-secondary">
                        CANCEL
                    </button>
                    <button type="button" class="button button-small">SAVE</button>
                </div>
            </div>
        </div>
    `),

    initialize() {
        this.render();
    },

    render() {
        this.$el.html(this.template(this.model.attributes));

        return this;
    },

    toggleTask() {
        if (this.model.get('status') == 'open') {
            this.closeTask();
        } else {
            this.closeTask();
        }
    },

    closeTask() {
        this.model.set('closed');
        this.$el.find('.task').addClass('completed');

        return $.ajax({
            method: 'post',
            headers: getJsonHeader(),
            url: `/task/close/${this.model.get('id')}`,
        });
    },

    openTask() {
        this.model.set('open');
        this.$el.find('.task').removeClass('completed');

        return $.ajax({
            method: 'post',
            headers: getJsonHeader(),
            url: `/task/close/${this.model.get('id')}`,
        });
    },

    removeTask() {
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        })
        .then(this.sendDeleteRequest.bind(this))
        .then(this.showDeletedTaskFeedback.bind(this))
        .then(this.removeElement.bind(this))
        .catch(this.handleErrors.bind(this));
    },

    sendDeleteRequest() {
        return $.ajax({
            url: '/task/' + this.model.get('id'),
            method: 'delete',
            headers: getCSRFHeader(),
        });
    },

    showDeletedTaskFeedback() {
        return swal(
            'Deleted!',
            'The task has been deleted.',
            'success'
        );
    },

    removeElement() {
        this.$el.slideUp('fast');
    },

    handleErrors(response = null) {
        if (_.isObject(response)) {
            return swal('Error!', response.responseJSON.data, 'error');
        }
    }
});