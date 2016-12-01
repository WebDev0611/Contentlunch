var teamMemberInviteModalView = Backbone.view.extend({
    template: _.template($('#modal-invite-team-member-template').html()),

    initialize: function() {
        this.render();
    },

    render: function() {
        this.$el.find('.modal').modal('show');
    }

})