<template>
    <div id="launch" class="modal fade" tabindex="-1" role="dialog" id='ideas-collaborator-modal'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="modal-close close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">INVITE COLLABORATORS</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <p class="text-gray text-center">
                                Select the users you want to collaborate with.
                            </p>
                            <div class="collaborators-list">
                                <loading v-show='loaded'></loading>
                            </div>
                            <div class="empty-collaborators-message text-center" v-show='loaded && !collaborators'>
                                <p>We couldn't find any other account members. Please use the field below to invite friends.</p>
                                <div class="inner">
                                    <div class="input-form-group">
                                        <label for="#">Invite</label>
                                        <input type="text" class="email-invites input" placeholder="One or more e-mail addresses separated by commas">
                                    </div>
                                    <div class="alert alert-danger alert-forms" style='display:none'>
                                        Please enter one or more email addresses.
                                    </div>
                                    <button class="send-invitation button button-extend text-uppercase">
                                        Send Invitation
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <button
                                class="button button-primary text-uppercase button-extend invite-users"
                                data-toggle="modal"
                                data-target="#ideas-collaborator-modal">Invite Users</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ideas-collaborators',

    }
</script>