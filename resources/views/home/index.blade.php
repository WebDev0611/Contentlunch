@extends('layouts.master')

@section('content')
<div class="workspace">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <div class="dashboard-notification-box">
                    <span class="dashboard-notification-box-count">
                        <i class="icon-checklist"></i>
                        35
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
            <div class="col-md-1">
                <div class="dashboard-donut-chart donut-chart primary pull-right">
                    <div class="slice one"></div>
                    <div class="slice two"></div>
                    <div class="chart-center">
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="dashboard-content-score">
                    <span>CURRENT<br>CONTENT SCORE</span>
                </div>
            </div>
            <div class="col-md-3 text-right">
                <div class="dashboard-layout-container">
                    <span>Writer / Editor Layout</span>
                    <ul class="dashboard-layout">
                        <li class="active">
                            <a href="javascript:;">
                                <i class="icon-chart"></i>
                            </a>
                        </li>
                        <li>
                            <a href="/home">
                                <i class="icon-itemlist"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">

                <div class="panel" id="tab-container">
                    <div class="dashboard-stats">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="#" class="dashboard-stats-label">Content Score History</label>
                                <div class="dashboard-stats-label-chart-wrapper"></div>
                            </div>
                            <div class="col-md-4">
                                <label for="#" class="dashboard-stats-label">Content Items</label>
                                <div class="dashboard-stats-label-chart-wrapper"></div>
                            </div>
                            <div class="col-md-4">
                                <label for="#" class="dashboard-stats-label">Stage Breakdown</label>
                                <div class="dashboard-stats-label-chart-wrapper"></div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-header">
                        <ul class="panel-tabs spacing">
                            <li class="active top-content">
                                <a href="javascript:;">Top Performing Content</a>
                            </li>
                            <li class="active-campaigns">
                                <a href="javascript:;">Active Campaigns</a>
                            </li>
                        </ul>
                    </div>

                    <div class="dashboard-content-box height-spec1">
                        <ul class="list-unstyled list-content" id="tab-contents-cont">
                        </ul>
                    </div>
                </div>

            </div>

            <div class="col-md-3">

                <div class="panel max-height" id="recent-ideas">

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

                <div class="panel max-height">
                    <div class="panel-header">
                        <h4 class="panel-sidebar-title-secondary">Activity Feed</h4>
                    </div>

                    <div class="panel-container" id="activity-feed-container"></div>

                </div>
            </div>
        </div>
    </div>
</div>

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

<script type="text/template" id="content-item-template">
    <div class="list-avatar">
      <div class="user-avatar">
        <img src="<%= image %>"/>
      </div>
    </div>
    <div class="list-title">
      <p><a href="#"> <%= title %></a></p>
    </div>
    <div class="list-datestamp">
      <p><span>LAUNCHED:</span> <%= launched %></p>
    </div>
    <div class="list-type">
      <i class="icon-type-blog"></i>
    </div>
    <div class="list-score">
      <p><%= performance %></p>
    </div>
</script>

<script type="text/template" id="campaign-item-template">
    <div class="list-avatar">
      <div class="user-avatar">
        <img src="<%= image %>"/>
      </div>
    </div>
    <div class="list-title">
      <p><a href="#"> <%= title %></a></p>
    </div>
    <div class="list-datestamp">
      <p><span>LAUNCHED:</span> <%= launched %></p>
    </div>
    <div class="list-type">
      <i class="icon-type-blog"></i>
    </div>
    <div class="list-score">
      <p><%= performance %></p>
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
@stop

@section('scripts')
<script src="/js/performance.js"></script>
@stop