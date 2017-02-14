(function() {

    var CollaboratorModel = Backbone.Model.extend({
        defaults: {
            profile_image: '/images/cl-avatar2.png'
        }
    });

    var CollaboratorCollection = Backbone.Collection.extend({
        model: CollaboratorModel,

        populateList: function(data) {
            this.fetchData().then(function(response) {
                this.add(response.data.map(this.createCollaboratorModel));
            }.bind(this));
        },

        fetchData: function() {
            return $.ajax({
                method: 'get',
                url: '/api/ideas/' + idea_obj.id + '/collaborators',
                headers: getCSRFHeader(),
            });
        },

        createCollaboratorModel: function(collaborator) {
            return new CollaboratorModel({
                name: collaborator.name,
                profile_image: collaborator.profile_image || '/images/cl-avatar2.png',
                email: collaborator.email,
            });
        },
    });

    var CollaboratorView = Backbone.View.extend({
        template: _.template(
            "<img src='<%= profile_image %>' title='<%= name %>' alt='<%= name %>'>"
        ),
        tagName: 'li',
        render: function() {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        },
    })

    var CollaboratorModalView = Backbone.View.extend({
        template: _.template($('#ideas-collaborator-checkbox').html()),
        tagName: 'div',
        render: function() {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        },
    });

    var AddCollaboratorModalView = Backbone.View.extend({
        events: {
            'click .invite-users': 'submit',
        },
        template: _.template($('#ideas-collaborator-modal-view').html()),
        data: {
            users: []
        },

        initialize: function() {
            this.render();
            this.fetchData();
        },

        fetchData: function() {
            return $.ajax({
                method: 'get',
                url: '/api/ideas/' + idea_obj.id + '/collaborators?possible_collaborators=1',
                headers: getCSRFHeader(),
            })
            .then(function(response) {
                this.clearList();
                this.data.users = response.data;
                this.renderCheckboxes();
            }.bind(this));;
        },

        render: function() {
            this.$el.html(this.template(this.data));
            $('body').prepend(this.el);

            return this;
        },

        clearList: function() {
            this.getList().html('');
        },

        getList: function() {
            return this.$el.find('.collaborators-list');
        },

        renderCheckboxes: function() {
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
                var userCheckbox = new CollaboratorModalView({
                    model: new CollaboratorModel(user)
                });

                userCheckbox.render();

                collaboratorsList.append(userCheckbox.el);
            });
        },

        showModal: function() {
            this.$el.on('hidden.bs.modal', this.remove.bind(this));
            this.$el.find('.modal').modal('show');
        },

        dismissModal: function() {
            this.$el.find('.modal').modal('hide');
        },

        submit: function() {
            $.ajax({
                method: 'post',
                url: '/api/ideas/' + idea_obj.id + '/collaborators',
                headers: getCSRFHeader(),
                data: {
                    collaborators: this.getCheckedCollaborators()
                },
            })
            .then(function(response) {
                $('#ideas-collaborator-list').html('');
                collaborators.populateList();
                this.dismissModal();
            }.bind(this));
        },

        getCheckedCollaborators: function() {
            return this.$el.find(':checked')
                .toArray()
                .map(function(checkbox) {
                    return $(checkbox).data('id');
                });
        }
    });

    $('#open-collab-modal').click(function(event) {
        event.preventDefault();
        var collabModal = new AddCollaboratorModalView();
        collabModal.showModal();
    });

    var collaborators = new CollaboratorCollection();

    collaborators.on('add', function(model) {
        var result = new CollaboratorView({
            model: model
        });

        result.render();

        $('#ideas-collaborator-list').append(result.el);
    });

    collaborators.populateList();

})();