var teamMemberInviteModalView = Backbone.View.extend({
    initialize: function() {
        console.log('team member invite modal initialized');
        this.render();
    },

    render: function() {
        this.$el.modal('show');

        return this;
    }

});