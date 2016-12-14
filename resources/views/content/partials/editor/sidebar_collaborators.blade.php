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
