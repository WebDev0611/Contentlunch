(function() {

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

    var CollaboratorView = Backbone.View.extend({
        template: _.template($('#sidebar-collaborator-view').html()),
        tagName: 'li',
        render: function() {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
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