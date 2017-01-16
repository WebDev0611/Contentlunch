@extends('layouts.master')

@section('content')

<div class="workspace">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <div class="dashboard-notification-box">
                    <span class="dashboard-notification-box-count">
                        <i class="icon-checklist"></i>
                        <span id="incomplete-tasks">0</span>
                    </span>
                    <span>Incomplete <br> Tasks</span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="dashboard-notification-box">
                    <span class="dashboard-notification-box-count">
                        <i class="icon-idea"></i>
                        <span class="idea-count">0</span>
                    </span>
                    <span>New Ideas <br> Posted</span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="dashboard-notification-box">
                    <span class="dashboard-notification-box-count">
                        <i class="icon-navigation-dashboard"></i>
                        0
                    </span>
                    <span>New Content <br> Pieces Posted</span>
                </div>
            </div>
            <div class="col-md-3 text-right">
            <!--
                <div class="dashboard-layout-container">
                    <span>Writer / Editor Layout</span>
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
                -->
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
                            <!--<li class="campaigns">
                                <a href="#">Campaigns</a>
                            </li> -->
                            <!-- out for now
                            <li>
                                <a href="javascript:;">Recently Viewed</a>
                            </li>
                            -->
                        </ul>
                    </div>

                </div>
            </div>
            <!--
            <div class="col-md-3">
                <div class="panel max-height">
                    <div class="panel-header">
                        <h4 class="panel-sidebar-title-secondary">Activity Feed</h4>
                    </div>

                    <div class="panel-container" id="activity-feed-container"></div>

                </div>
            </div>
            -->
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

                <div class="panel" id="team-members-container">
                    <div class="panel-header">
                        <h4 class="panel-sidebar-title-secondary">
                            Team Members
                            <a href="#" class='team-member-modal-opener'>
                                INVITE
                                <i class="icon-edit-user"></i>
                            </a>
                        </h4>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

@include('home.partials.invite_team_member_modal')
<script>
    var my_campaigns = {!! $mycampaigns !!};
    var my_tasks = {!! $tasks !!};
    var account_tasks = {!! $accountTasks !!};
</script>
@stop

@section('scripts')
<script src="/js/dashboard.js"></script>
@stop