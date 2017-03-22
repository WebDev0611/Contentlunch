var teamMemberInviteModalView = Backbone.View.extend({
    events: {
        'click .send-invitation': 'sendInvites',
        'hidden.bs.modal': 'teardown'
    },

    initialize() {
        this.render();
    },

    render() {
        this.$el.modal('show');

        return this;
    },

    teardown(){
        this.$el.find('.email-invites').val('');
        this.$el.removeData().unbind();
    },

    sendInvites() {
        let emails = this.$el.find('.email-invites').val();

        if (!emails) {
            this.$el.find('.alert').slideDown('fast');
            return;
        }

        this.emailsCount = emails.split(',').length;

        let sendButton = this.$el.find('.send-invitation');
        let sendButtonText = sendButton.text();
        sendButton.attr("disabled", true).text('Sending...');

        return $.ajax({
            headers: getCSRFHeader(),
            method: 'post',
            url: '/invite/emails',
            data: {
                emails: emails
            },
            complete: function (data) {
                sendButton.attr("disabled", false).text(sendButtonText)
            }
        })
        .then(this.hideModal.bind(this))
        .then(this.showFeedback.bind(this))
        .catch(response => {
            if (response.status === 403) {
                showUpgradeAlert(response.responseJSON.data);
            } else {
                swal('Error!', response.responseJSON.data, 'error')
            }
        });
    },

    hideModal() {
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