var teamMemberInviteModalView = Backbone.View.extend({
    events: {
        'click .send-invitation': 'sendInvites'
    },

    initialize() {
        this.render();
    },

    render() {
        this.$el.modal('show');

        return this;
    },

    sendInvites() {
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
            },
        })
        .then(this.hideModal.bind(this))
        .then(this.showFeedback.bind(this));
    },

    hideModal(response) {
        this.$el.modal('hide');
    },

    showFeedback(response) {
        var alert = "<div class='alert alert-success alert-forms' id='dashboard-feedback' style='display:none'>" +
            "Invites sent!" +
        "</div>";

        $('#dashboard-feedback').remove();
        $(alert).prependTo('.workspace');
        $('#dashboard-feedback').slideDown();

        setTimeout(function() {
            $('#dashboard-feedback').slideUp();
        }, 3000);
    }

});