<template>
    <div class="modal fade"
        id="modal-invite-guests"
        tabindex="-1"
        role="dialog"
        aria-labelledby="Invite Client">

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Invite Client</h4>
                </div>
                <div class="modal-body">

                    <div class="inner">
                        <p class="intro">
                            Invite your clients to ContentLaunch to share content, plans and more.
                        </p>

                        <div class="input-form-group">
                            <label for="#">Invite</label>
                            <input type="text"
                                v-model='emails'
                                class="email-invites input"
                                placeholder="One or more e-mail addresses separated by commas">
                        </div>

                        <div class="alert alert-danger alert-forms" v-show='showError'>
                            Please enter one or more email addresses.
                        </div>

                        <button class="send-invitation button button-extend text-uppercase" @click='inviteGuests' v-show='!loading'>
                            Send Invitation
                        </button>

                        <loading v-show='loading'></loading>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Loading from '../Loading.vue';

    export default {
        name: 'guests-invite-modal',

        components: { Loading },

        props: [ 'contentId', 'type' ],

        data() {
            return {
                emails: '',
                showError: false,
                loading: false,
            };
        },

        methods: {
            inviteUrl() {
                switch(this.type) {
                    case 'content': return `/api/contents/${this.contentId}/guests`;
                    case 'campaign': //
                    case 'idea': //

                    default:
                        console.error(
                            "Error! Invalid type for the <guests-invite-modal> component. " +
                            "Use either 'campaign', 'content' or 'idea'."
                        );

                        return null;
                }
            },

            inviteGuests() {
                if (!this.emails || this.emails === '') {
                    this.showError = true;
                    return this.showError;
                }

                this.loading = true;

                $.post(this.inviteUrl(), { emails: this.emails }).then(response => {
                    this.closeModal();
                    this.showModalFeedback();
                });
            },

            closeModal() {
                $('#modal-invite-guests').modal('hide');
                this.loading = false;
            },

            showModalFeedback() {
                let plural = this.emails.split(',').length > 1;
                let message = plural
                    ? 'The invites were sent. The users will be able to join this piece of content as a client'
                    : 'The invite was sent. The user will be able to join this piece of content as a client';

                let header = plural ? 'Clients invited!' : 'Client invited!';

                swal(header, message, 'success');
                this.emails = '';
            }
        },
    }
</script>