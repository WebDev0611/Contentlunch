<div class="sidepanel-body">
    <div class="pane-users">
        <ul class="list-unstyled list-users" id='sidebar-collaborator-list'>

        </ul>
    </div>
</div>

<script type='text/template' id='sidebar-collaborator-view'>
    <a href="#">
        <div class="user-avatar">
            <img src="<%= profile_image %>" alt="">
        </div>
        <p class="title"><%= name %></p>
        <p class="email"><%= email %></p>
    </a>
</script>

<script type='text/template' id='sidebar-collaborator-modal-view' style='display:none'>
    <div id="launch" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="modal-close close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">LAUNCH CONTENT</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <p class="text-gray text-center">
                                Select the users you want to collaborate with.
                            </p>
                            <% _.each(users, function(user) { %>
                            <label class="checkbox-tag">
                                <input type="checkbox">
                                <span><%= user.name %> (<%= user.email %>)</span>
                            </label>
                            <% }); %>
                            <!--
                            <div class="form-group text-center">
                                <a href="#" class="link-gray">
                                    ADD NEW
                                    <i class="icon-add"></i>
                                </a>
                            </div>
                            -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <button
                                class="button button-primary text-uppercase button-extend invite-users"
                                data-toggle="modal"
                                data-target="#launchCompleted">Invite Users</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>