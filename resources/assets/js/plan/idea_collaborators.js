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
            "<img src='<%= profile_image %>' title='<%= name %>' alt='<%= name %>'> <p><%= name %></p>"
        ),
        tagName: 'li',
        render: function() {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        },
    })

    $('#open-collab-modal').click(function(event) {
        event.preventDefault();
        var collabModal = new AddIdeaCollaboratorModalView();
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