<template>
    <div class="inner">
        <div class="input-form-group">
            <label>Invite</label>
            <input type="text" v-model='emails' class="email-invites input" placeholder="One or more e-mail addresses separated by commas">
        </div>
        <div class="alert alert-danger alert-forms" v-show='showAlert'>
            Please enter one or more email addresses.
        </div>
        <button class="send-invitation button button-extend text-uppercase" @click='inviteUsers' v-show='!loading'>
            Send Invitation
        </button>
        <loading v-show='loading'></loading>
    </div>
</template>

<script>
    export default {
        name: 'invite-form',

        data() {
            return {
                emails: '',
                showAlert: false,
                loading: false,
            };
        },

        methods: {
            inviteUsers() {
                if (this.emails === '') {
                    this.showAlert = true;
                    return false;
                }

                let payload = { emails: this.emails };

                this.loading = true;

                $.post('/invite/emails', payload)
                    .then(this.handleSuccess.bind(this))
                    .catch(this.handleError.bind(this));
            },

            handleSuccess(response) {
                let emailsCount = this.emails.split(',').length;

                this.$emit('invited');
                this.emails = '';
                this.loading = false;
                this.showSuccessFeedback(emailsCount);
            },

            handleError(response) {
                this.loading = false;
                this.$emit('invited');

                if (response.status === 403) {
                    showUpgradeAlert(response.responseJSON.data);
                } else {
                    swal('Error!', response.responseJSON.data, 'error')
                }
            },

            showSuccessFeedback(count) {
                let title = 'Invites Sent!';
                let message = 'The invites were sent successfully.';

                if (count === 1) {
                    title = 'Invite Sent!';
                    message = 'The invite was sent successfully.';
                }

                swal(title, message, 'success');
            },
        },
    }
</script>