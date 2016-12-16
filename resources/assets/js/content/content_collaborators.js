(function() {

    /**
     * Models and data
     */
    var contentId = $('input[name=content_id]').val();

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
                url: '/api/contents/' + contentId + '/collaborators',
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

        submit: function() {
            var checked = this.$el.find(':checked');
            console.log(checked);
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