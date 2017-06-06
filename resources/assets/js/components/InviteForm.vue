<template>
    <div class="inner">
        <div class="input-form-group">
            <label>Invite</label>
            <input type="text" v-model='emails' class="email-invites input" placeholder="One or more e-mail addresses separated by commas">
        </div>
        <div class="alert alert-danger alert-forms" v-show='showAlert'>
            Please enter one or more email addresses.
        </div>
        <button class="send-invitation button button-extend text-uppercase" @click='inviteUsers'>
            Send Invitation
        </button>
    </div>
</template>

<script>
    export default {
        name: 'invite-form',

        data() {
            return {
                emails: '',
                showAlert: false,
            };
        },

        methods: {
            inviteUsers() {
                if (emails === '') {
                    this.showAlert = true;
                    return false;
                }

                this.$emit('invited');
            }
        },
    }
</script>