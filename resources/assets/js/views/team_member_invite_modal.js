var teamMemberInviteModalView = Backbone.View.extend({
    events: {
        'click .send-invitation': 'sendInvites'
    },

    initialize: function() {
        this.render();
    },

    render: function() {
        this.$el.modal('show');

        return this;
    },

    sendInvites: function() {
        var emails = this.$el.find('.email-invites').val();

        if (!emails) {
            this.$el.find('.alert').slideDown('fast');
            return;
        }

        return $.ajax({
            headers: getCSRFHeader(),
            method: 'post',
            url: '/invite/emails',
            data: {
                emails: emails
            }
        });
    },

});