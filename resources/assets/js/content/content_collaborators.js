(function() {

    if (!isContentEditorPage()) {
        return false;
    }

    /**
     * Models and data
     */
    var contentId = $('input[name=content_id]').val();

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
                url: '/api/contents/' + contentId + '/collaborators',
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

    /**
     * Views
     */
    var CollaboratorView = Backbone.View.extend({
        template: _.template($('#sidebar-collaborator-view').html()),
        tagName: 'li',
        render: function() {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        },
    });

    var SidebarView = Backbone.View.extend({
        events: {
            'click #add-person-to-content': 'openAddPersonModal',
        },

        initialize: function() {
            this.render();
        },
        render: function() {
            return this;
        },

        openAddPersonModal: function(event) {
            event.preventDefault();
            event.stopPropagation();
            var modal = new AddCollaboratorModalView();
            modal.render();
            $('body').prepend(modal.el);
            modal.showModal();
        },
    });

    new SidebarView({ el: '#editor-panel-sidebar' });

    var CollaboratorModalView = Backbone.View.extend({
        template: _.template($('#sidebar-collaborator-checkbox').html()),
        tagName: 'div',
        render: function() {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        },
    })

    var AddCollaboratorModalView = Backbone.View.extend({
        events: {
            'click .invite-users': 'submit',
        },
        template: _.template($('#sidebar-collaborator-modal-view').html()),
        data: {
            users: []
        },

        initialize: function() {
            this.clearList();
            this.render();
            this.fetchData();
        },

        render: function() {
            this.$el.html(this.template(this.data));
            return this;
        },

        getList: function() {
            return this.$el.find('.collaborators-list');
        },

        fetchData: function() {
            $.ajax({
                method: 'get',
                url: '/api/contents/' + contentId + '/collaborators?possible_collaborators=1',
                headers: getCSRFHeader(),
            })
            .then(function(response) {
                this.clearList();
                this.data.users = response.data;
                this.renderCheckboxes();
            }.bind(this));
        },

        clearList: function() {
            this.getList().html('');
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

        dismissModal: function() {
            this.$el.find('.modal').modal('hide');
        },

        submit: function() {
            $.ajax({
                method: 'post',
                url: '/api/contents/' + contentId + '/collaborators',
                headers: getCSRFHeader(),
                data: {
                    authors: this.getCheckedCollaborators()
                },
            })
            .then(function(response) {
                $('#sidebar-collaborator-list').html('');
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

    var collaborators = new CollaboratorCollection();

    collaborators.on('add', function(model) {
        var result = new CollaboratorView({
            model: model
        });

        result.render();

        $('#sidebar-collaborator-list').append(result.el);
    });

    collaborators.populateList();

    function isContentEditorPage() {
        return $('#sidebar-collaborator-modal-view').length > 0 && $('#sidebar-collaborator-view').length > 0;
    }

})();