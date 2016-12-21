(function() {

    var CollaboratorModel = Backbone.Model.extend({
        defaults: {
            profile_image: '/images/avatar.jpg'
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
                profile_image: collaborator.profile_image || '/images/avatar.jpg',
                email: collaborator.email,
            });
        },
    });

    var CollaboratorModalView = Backbone.View.extend({
        template: _.template($('#ideas-collaborator-checkbox').html()),
        tagName: 'div',
        render: function() {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        },
    });

    var AddCollaboratorModalView = Backbone.View.extend({
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
    });

    $('#open-collab-modal').click(function(event) {
        event.preventDefault();
        var collabModal = new AddCollaboratorModalView();
        collabModal.showModal();
    });

})();