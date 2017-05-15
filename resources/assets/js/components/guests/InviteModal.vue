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
                    <h4 class="modal-title" id="myModalLabel">Invite Team Member</h4>
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

                        <div class="alert alert-danger alert-forms">
                            Please enter one or more email addresses.
                        </div>

                        <button class="send-invitation button button-extend text-uppercase">
                            Send Invitation
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'guests-invite-modal',

        props: [ 'contentId', 'type' ],

        data() {
            return {
                emails: '',
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
                let url = this.inviteUrl();

                console.log('inviting user!');
            }
        },
    }
</script>