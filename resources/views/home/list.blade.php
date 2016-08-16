@extends('layouts.master')

@section('content')

<div class="workspace">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <div class="dashboard-notification-box">
                    <span class="dashboard-notification-box-count">
                        <i class="icon-checklist"></i>
                        <span id="incomplete-tasks"></span>
                    </span>
                    <span>Incomplete <br> Tasks</span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="dashboard-notification-box">
                    <span class="dashboard-notification-box-count">
                        <i class="icon-idea"></i>
                        5
                    </span>
                    <span>New Ideas<br> Posted</span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="dashboard-notification-box">
                    <span class="dashboard-notification-box-count">
                        <i class="icon-navigation-dashboard"></i>
                        5
                    </span>
                    <span>New Content <br>Pieces Posted</span>
                </div>
            </div>
            <div class="col-md-6 text-right">
                <div class="dashboard-layout-container">
                    <span>Writer / Editor Layout</span>
                    <ul class="dashboard-layout">
                        <li>
                            <a href="/dashboard">
                                <i class="icon-chart"></i>
                            </a>
                        </li>
                        <li class="active">
                            <a href="javascript:;">
                                <i class="icon-itemlist"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6" id="tab-container">
                <div class="panel">
                    <div class="panel-header">
                        <ul class="panel-tabs spacing">
                            <li class="active my-tasks">
                                <a href="#">My tasks</a>
                            </li>
                            <li  class="all-tasks">
                                <a href="#">All tasks</a>
                            </li>
                            <li class="campaigns">
                                <a href="#">Campaigns</a>
                            </li>
                            <!-- out for now 
                            <li>
                                <a href="javascript:;">Recently Viewed</a>
                            </li>
                            -->
                        </ul>
                    </div>

                </div>
            </div>
            <div class="col-md-3">
                <div class="panel max-height">
                    <div class="panel-header">
                        <h4 class="panel-sidebar-title-secondary">Activity Feed</h4>
                    </div>

                    <div class="panel-container" id="activity-feed-container"></div>

                </div>
            </div>

            <div class="col-md-3" id="misc-container">
                <div class="panel" id="recent-ideas">
                    <div class="panel-header">
                        <h4 class="panel-sidebar-title-secondary">
                            Recent Ideas
                            <a href="/plan/ideas">
                                See All
                                <i class="icon-arrow-right"></i>
                            </a>
                        </h4>
                    </div>


                </div>
            </div>

        </div>
    </div>
</div>

<script type="text/template" id="task-template">
    <div class="dashboard-tasks-container">
        <div class="dashboard-tasks-cell">
            <img src="<%= image %>" alt="#" class="dashboard-tasks-img">
        </div>
        <div class="dashboard-tasks-cell">
            <h5 class="dashboard-tasks-title">
                <%= title %>
            </h5>
            <span class="dashboard-tasks-text">
                <%= body %>
            </span>
            <ul class="dashboard-tasks-list">
                <li>DUE IN: <strong><%= due %></strong></li>
                <li>
                    STAGE:
                    <i class="dashboard-tasks-list-icon primary icon-idea"></i>
                    <i class="dashboard-tasks-list-icon tertiary icon-content"></i>
                    <i class="dashboard-tasks-list-icon tertiary icon-alert"></i>
                    <i class="dashboard-tasks-list-icon tertiary icon-share"></i>
                </li>
                <li>
                    <a href="#"><strong>Edit Content</strong></a>
                </li>
            </ul>
        </div>
        <div class="dashboard-tasks-cell">
            <% 
            var active = '';
            if( (new Date().getTime() - timeago) <= 60*10*1000 ){
                active = 'active';
            } %>
            <span class="dashboard-tasks-text small <%=active%>">
                <% if( (new Date().getTime() - timeago) <= 60*10*1000 ){%>
                    JUST NOW
                <% }else{ %>
                    <% if( ( new Date().getTime() - timeago ) / ( 60*60*1000 ) >= 24 ){ %>
                        <%= Math.floor(( new Date().getTime() - timeago ) / (60*60*1000*24)) %> DAYS AGO
                    <% }else if( (new Date().getTime() - timeago ) <= (60*60*1000) ){ %>
                        <%= Math.floor(( new Date().getTime() - timeago ) / (60*1000)) %> MINUTES AGO
                    <% }else{ %>
                        <%= Math.floor(( new Date().getTime() - timeago ) / (60*60*1000)) %> HOURS AGO
                    <% } %>
                <% } %>
            </span>
        </div>
    </div>
</script>


<script type="text/template" id="activity-item-template">
    <div class="plan-activity-box-img">
        <img src="<%= image %>" alt="#">
    </div>
    <div class="plan-activity-box">
        <span class="plan-activity-title">
            <a href="#"><%= who %></a> <%= action %>
            <a href="#"> <%= title %></a> on
            <a href="#"><%= content %></a>
        </span>
        <p class="plan-activity-text">
            <%= body %>
        </p>
    </div>
</script>

<script type="text/template" id="recent-template">
    <div class="dashboard-ideas-cell">
        <img src="<%= image %>" alt="#" class="dashboard-tasks-img">
    </div>
    <div class="dashboard-ideas-cell">
        <p class="dashboard-ideas-text"><%= title %></p>
        <span class="dashboard-ideas-text small"><%= timeago %></span>
    </div>
    <div class="dashboard-ideas-cell hidden idea-hover">
        <div class="dashboard-ideas-dropdown">
            <button type="button" class="button button-action" data-toggle="dropdown">
                <i class="icon-add-circle"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li>
                    <a href="#">Write It</a>
                </li>
            </ul>
        </div>
    </div>
</script>

<script type="text/template" id="team-members-template">
 <div class="panel">
        <div class="panel-header">
            <h4 class="panel-sidebar-title-secondary">
                Team Members
                <a href="#">
                    INVITE
                    <i class="icon-edit-user"></i>
                </a>
            </h4>
        </div>
        <div class="dashboard-members-container">
            <div class="dashboard-ideas-cell">
                <img src="/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
            </div>
            <div class="dashboard-members-cell">
                <p class="dashboard-ideas-text">Jason Simmons</p>
                <span class="dashboard-members-text small">jasonsimm@google.com</span>
            </div>
            <div class="dashboard-members-cell">
                <span class="dashboard-ideas-text small">
                    <i class="icon-checklist"></i>
                    35
                </span>
            </div>
        </div>
        <div class="dashboard-members-container">
            <div class="dashboard-ideas-cell">
                <img src="/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
            </div>
            <div class="dashboard-members-cell">
                <p class="dashboard-ideas-text">Jason Simmons</p>
                <span class="dashboard-members-text small">jasonsimm@google.com</span>
            </div>
            <div class="dashboard-members-cell">
                <span class="dashboard-ideas-text small">
                    <i class="icon-checklist"></i>
                    35
                </span>
            </div>
        </div>
        <div class="dashboard-members-container">
            <div class="dashboard-ideas-cell">
                <img src="/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
            </div>
            <div class="dashboard-members-cell">
                <p class="dashboard-ideas-text">Jason Simmons</p>
                <span class="dashboard-members-text small">jasonsimm@google.com</span>
            </div>
            <div class="dashboard-members-cell">
                <span class="dashboard-ideas-text small">
                    <i class="icon-checklist"></i>
                    35
                </span>
            </div>
        </div>
        <div class="dashboard-members-container">
            <div class="dashboard-members-cell">
                <img src="/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
            </div>
            <div class="dashboard-members-cell">
                <p class="dashboard-ideas-text">Jason Simmons</p>
                <span class="dashboard-members-text small">jasonsimm@google.com</span>
            </div>
            <div class="dashboard-members-cell">
                <span class="dashboard-ideas-text small">
                    <i class="icon-checklist"></i>
                    35
                </span>
            </div>
        </div>
    </div>
</script>


@stop


@section('scripts')
<script src="/js/dashboard.js"></script>
@stop