(function() {

    /**
     * Models and data
     */
    var fakeData = [
        {
            name: 'Admin',
            email: 'admin@test.com',
            profile_image: 'https://s3.amazonaws.com/elasticbeanstalk-us-east-1-244315376647/attachment/1/profile/20161129_212514_administrator.jpg'
        },
        {
            name: 'John Wick',
            email: 'john@wick.com',
        },
    ];

    var CollaboratorModel = Backbone.Model.extend({
        defaults: {
            profile_image: '/images/avatar.jpg'
        }
    });

    var CollaboratorCollection = Backbone.Collection.extend({
        model: CollaboratorModel,

        populateList: function(data) {
            this.add(data.map(this.createCollaboratorModel));
        },

        createCollaboratorModel: function(collaborator) {
            return new CollaboratorModel(collaborator);
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

    var AddCollaboratorModalView = Backbone.View.extend({
        template: _.template($('#sidebar-collaborator-modal-view').html()),
        data: {
            users: [
                {
                    name: 'Admin',
                    email: 'admin@test.com',
                    profile_image: 'https://s3.amazonaws.com/elasticbeanstalk-us-east-1-244315376647/attachment/1/profile/20161129_212514_administrator.jpg'
                },
                {
                    name: 'John Wick',
                    email: 'john@wick.com',
                },
            ]
        },

        initialize: function() {
            this.render();
        },

        render: function() {
            this.$el.html(this.template(this.data));
            this.$el.on('hidden.bs.modal', this.remove.bind(this));
            return this;
        },

        showModal: function() {
            this.$el.find('.modal').modal('show');
        },
    });

    var collaborators = new CollaboratorCollection();

    collaborators.on('add', function(model) {
        var result = new CollaboratorView({
            model: model
        });

        result.render();

        $('#sidebar-collaborator-list').append(result.el);
    });

    collaborators.populateList(fakeData);

})();