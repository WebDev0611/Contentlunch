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
        let emails = this.$el.find('.email-invites').val();

        if (!emails) {
            this.$el.find('.alert').slideDown('fast');
            return;
        }

        this.emailsCount = emails.split(',').length;

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
        const alert = _.template(`
            <div class='alert alert-success alert-forms' id='dashboard-feedback' style='display:none'>
                <%= emailsCount > 1 ? 'Invites' : 'Invite' %> sent!
            </div>
        `);
        const element = alert({ emailsCount : this.emailsCount });

        $('#dashboard-feedback').remove();
        $(element).prependTo('.workspace');
        $('#dashboard-feedback').slideDown();

        setTimeout(function() {
            $('#dashboard-feedback').slideUp();
        }, 3000);
    }

});