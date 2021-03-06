<div class="modal fade"
    id="modal-invite-team-member"
    tabindex="-1"
    role="dialog"
    aria-labelledby="Invite Client">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Invite Team Member</h4>
            </div>
            <div class="modal-body">

                <div class="inner">
                    <p class="intro">Invite team members to {{ trans('messages.company') }} to share content, plans and more.</p>

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
</div>
