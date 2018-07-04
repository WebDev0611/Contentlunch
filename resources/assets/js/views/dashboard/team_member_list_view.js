'use strict';

var team_member_list_view = Backbone.View.extend({
    events:{
        "click .team-member-modal-opener": "openTeamMemberInviteModal",
    },

    initialize: function() {
        this.render();
    },

    render: function() {
        this.collection.each(function(model) {
            var team_member = new team_member_view({ model: model });
            this.$el.append(team_member.$el);
        }.bind(this));

        return this;
    },

    openTeamMemberInviteModal: function() {
        var modal = new teamMemberInviteModalView({ el: '#modal-invite-team-member' });
    }
});