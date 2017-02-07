'use strict';

var AddCampaignCollaboratorModal = Backbone.View.extend({
    events: {
        'click .invite-users': 'submit',
    },

    data: {
        users: [],
        campaignId: null,
        collection: [],
    },

    template: _.template(`
        <div id="launch" class="modal fade" tabindex="-1" role="dialog">
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
                            <div class="col-md-6 col-md-offset-3">
                                <p class="text-gray text-center">
                                    Select the users you want to collaborate with.
                                </p>
                                <div class="collaborators-list">
                                    <img src="/images/ring.gif" class='loading-relative' alt="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <button
                                    class="button button-primary text-uppercase button-extend invite-users"
                                    data-toggle="modal"
                                    data-target="#sidebar-collaborator-modal">Invite Users</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `),

    initialize(options) {
        this.data.campaignId = options.campaignId;
        this.data.collection = options.collection;
        this.data.form = options.form;
        this.data.parentList = options.parentList;
        this.clearList();
        this.render();
        this.fetchData();
    },

    render() {
        this.$el.html(this.template());

        return this;
    },

    getList() {
        return this.$el.find('.collaborators-list');
    },

    fetchUrl() {
        return this.data.campaignId ?
            `/api/campaigns/${this.data.campaignId}/collaborators?possible_collaborators=1` :
            `/api/campaigns/collaborators`;
    },

    fetchData() {
        $.ajax({
            method: 'get',
            url: this.fetchUrl(),
            headers: getCSRFHeader(),
        })
        .then(function(response) {
            this.clearList();
            this.data.users = response.data;
            this.renderCheckboxes();
        }.bind(this));
    },

    clearList() {
        this.getList().html('');
    },

    renderCheckboxes() {
        this.data.users.forEach(function(user) {
            user.is_collaborator = user.is_collaborator || (!this.data.campaignId && user.is_logged_user);
            let model = new CollaboratorModel(user);
            let userCheckbox = new CollaboratorModalView({ model });

            userCheckbox.render();

            this.getList().append(userCheckbox.el);
        }.bind(this));
    },

    showModal() {
        this.$el.on('hidden.bs.modal', this.remove.bind(this));
        this.$el.find('.modal').modal('show');
    },

    dismissModal() {
        this.$el.find('.modal').modal('hide');
    },

    submit() {
        if (this.data.campaignId) {
            this.saveCollaboratorsToAPI().then(this.refreshCollaborators().bind(this))
        } else {
            this.saveCollaboratorsToDOM();
            this.dismissModal();
        }
    },

    saveCollaboratorsToAPI() {
        return $.ajax({
            method: 'post',
            url: `/api/campaigns/${this.campaignId}/collaborators`,
            headers: getCSRFHeader(),
            data: {
                authors: this.getCheckedCollaborators()
            },
        });
    },

    saveCollaboratorsToDOM() {
        let field = $('input[name=collaborators]');
        let selected = this.getCheckedCollaborators();

        if (field.length) {
            field.val(selected.join(','));
        } else {
            let el = $('<input>', {
                type: 'hidden',
                name: 'collaborators',
                val: selected.join(','),
            });

            this.data.form.append(el);
            this.data.parentList.html('');

            let newUsers = selected.map(userId => _.find(this.data.users, { id: userId }));
            this.collection.add(newUsers);
        }
    },

    refreshCollaborators(response) {
        this.data.parentList.html('');
        this.collection.populateList(this.campaignId);
        this.dismissModal();
    },

    getCheckedCollaborators() {
        return this.$el.find(':checked')
            .toArray()
            .map(checkbox => $(checkbox).data('id'));
    }
});