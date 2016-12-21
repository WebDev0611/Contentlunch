<fieldset class="form-fieldset clearfix">
    <legend class="form-legend">Collaborators</legend>
    <ul class="images-list pull-left" id='ideas-collaborator-list'>

    </ul>

    @if ($is_collaborator)
    <button type="button" id='open-collab-modal' class="button button-action large pull-right">
        <i class="icon-add-circle"></i>
    </button>
    @endif
</fieldset>

<script type='text/template' id='ideas-collaborator-checkbox'>
    <label class="checkbox-tag">
        <input type="checkbox"
            data-id='<%= id %>'
            <% if (is_collaborator) { %>
                checked=checked
            <% } %>
            <% if (is_logged_user) { %>
                disabled="disabled"
            <% } %>>
        <span><%= name %></span>
    </label>
</script>

<script type='text/template' id='ideas-collaborator-modal-view'>
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
                        <div class="col-md-6 col-md-offset-3">
                            <p class="text-gray text-center">
                                Select the users you want to collaborate with.
                            </p>
                            <div class="collaborators-list">
                                <img src="/images/ring.gif" class='loading-relative' alt="">
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
</script>