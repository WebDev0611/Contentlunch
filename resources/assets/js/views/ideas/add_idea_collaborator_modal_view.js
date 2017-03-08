'use strict';

var AddIdeaCollaboratorModalView = Backbone.View.extend({
    events: {
        'click .invite-users': 'submit',
    },

    template: _.template(`
        <div id="launch" class="modal fade" tabindex="-1" role="dialog" id='ideas-collaborator-modal'>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="modal-close close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">INVITE COLLABORATORS</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <p class="text-gray text-center">
                                    Select the users you want to collaborate with.
                                </p>
                                <div class="collaborators-list">
                                    <img src="/images/ring.gif" class='loading-relative' alt="">
                                </div>
                                <div class="empty-collaborators-message text-center" style="display:none">
                                    <p>We couldn't find any other account members. Please use the field below to invite friends.</p>
    
                                    <div class="inner">
    
                                        <div class="input-form-group">
                                            <label for="#">Invite</label>
                                            <input type="text" class="email-invites input" placeholder="One or more e-mail addresses separated by commas">
                                        </div>
    
                                        <div class="alert alert-danger alert-forms" style='display:none'>
                                            Please enter one or more email addresses.
                                        </div>
    
                                        <button class="send-invitation button button-extend text-uppercase">
                                            Send Invitation
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <button
                                    class="button button-primary text-uppercase button-extend invite-users"
                                    data-toggle="modal"
                                    data-target="#ideas-collaborator-modal">Invite Users</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `),

    data: {
        users: [],
    },

    idea: {},

    initialize(options = {}) {
        this.idea = options.idea || {};
        this.collaborators = options.collaborators || [];
        this.render();
        this.fetchData();
    },

    getUrl() {
        return _.has(this.idea, 'id')
            ? `/api/ideas/${this.idea.id}/collaborators`
            : `/api/ideas/collaborators`;
    },

    fetchData() {
        return $.ajax({
            method: 'get',
            url: this.getUrl() + '?possible_collaborators=1',
        })
        .then(function(response) {
            this.clearList();
            this.data.users = response.data;
            this.renderCheckboxes();
        }.bind(this));
    },

    render() {
        this.$el.html(this.template(this.data));
        $('body').prepend(this.el);

        return this;
    },

    clearList() {
        this.getList().html('');
    },

    getList() {
        return this.$el.find('.collaborators-list');
    },

    renderCheckboxes() {
        var collaboratorsList = this.getList();

        // If there are no possible collaborators
        if (this.data.users.length === 0) {
            $('.empty-collaborators-message').show();
            $('.button.invite-users').hide();
            var modal = new teamMemberInviteModalView({ el: '#launch' });
        } else {
            $('.empty-collaborators-message').hide();
            $('.button.invite-users').show();
        }

        this.data.users.forEach(function(user) {
            var userCheckbox = new IdeasCollaboratorModalView({
                model: new CollaboratorModel(user)
            });

            userCheckbox.render();

            collaboratorsList.append(userCheckbox.el);
        });
    },

    showModal() {
        this.$el.on('hidden.bs.modal', this.remove.bind(this));
        this.$el.find('.modal').modal('show');
    },

    dismissModal() {
        this.$el.find('.modal').modal('hide');
    },

    submit() {
        if (!_.has(this.idea, 'id')) {
            this.sendSelectedViaEvent();
            this.dismissModal();
            return;
        }

        $.ajax({
            method: 'post',
            url: this.getUrl(),
            data: {
                collaborators: this.getCheckedCollaborators()
            },
        })
        .then(function(response) {
            $('#ideas-collaborator-list').html('');
            this.collaborators.populateList();
            this.dismissModal();
        }.bind(this));
    },

    sendSelectedViaEvent() {
        let checked = this.getCheckedCollaborators();
        let users = this.data.users.filter(user => checked.indexOf(user.id) >= 0);

        Backbone.trigger('idea_collaborators:selected', users);
    },

    getCheckedCollaborators() {
        return this.$el.find(':checked')
            .toArray()
            .map(function(checkbox) {
                return $(checkbox).data('id');
            });
    }
});