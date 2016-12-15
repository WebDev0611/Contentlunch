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
                                Here are the 5 content connections you can push out to, check the ones you want, and
                                click launch and they go out in real time to be published.
                                Need a “confirmation” screen as well.
                            </p>
                            <label for="dieselEngines1" class="checkbox-tag">
                                <input id="dieselEngines1" type="checkbox">
                                <span>Dwight’s Twitter Feed</span>
                            </label>
                            <label for="dieselEngines1" class="checkbox-tag">
                                <input id="dieselEngines1" type="checkbox">
                                <span>Dwight’s Twitter Feed</span>
                            </label>
                            <label for="dieselEngines1" class="checkbox-tag">
                                <input id="dieselEngines1" type="checkbox">
                                <span>Dwight’s Twitter Feed</span>
                            </label>
                            <label for="dieselEngines1" class="checkbox-tag">
                                <input id="dieselEngines1" type="checkbox">
                                <span>Dwight’s Twitter Feed</span>
                            </label>
                            <div class="form-group text-center">
                                <a href="#" class="link-gray">
                                    ADD NEW
                                    <i class="icon-add"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <button class="button button-primary text-uppercase button-extend"  data-toggle="modal" data-target="#launchCompleted">LAUNCH</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>