@extends('layouts.master')

@section('content')

    @include('elements.freemium-alert')

    <div class="workspace">
        <div class="container-fluid">

            @can('guests-denied')
            <div class="row">
                <div class="col-md-2">
                    <incomplete-task-counter></incomplete-task-counter>
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
                        <span class="content-count">0</span>
                    </span>
                        <span>New Content <br> Pieces Posted</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <content-orders-counter></content-orders-counter>
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
            @endcan

            <div class="row">
                <div class="col-md-6" id="tab-container">
                    <div class="panel">
                        <div class="panel-header">
                            <ul class="panel-tabs spacing">
                                @can('guests-denied')
                                <li class="active my-tasks">
                                    <a href="#my-tasks" role='tab' data-toggle='tab'>My tasks</a>
                                </li>
                                <li class="all-tasks">
                                    <a href="#all-tasks" role='tab' data-toggle='tab'>All tasks</a>
                                </li>
                                <li class="campaigns">
                                    <a href="#campaigns" role='tab' data-toggle='tab'>Campaigns</a>
                                </li>
                                @else
                                <li class="campaigns active">
                                    <a href="#campaigns" role='tab' data-toggle='tab'>Campaigns</a>
                                </li>
                                @endcan

                                <!-- out for now
                                <li>
                                    <a href="javascript:;">Recently Viewed</a>
                                </li>
                                -->
                            </ul>
                        </div>
                        <div class="tab-content">
                            @can('guests-denied')
                            <div class="tab-pane active" role='tabpanel' id='my-tasks'>
                                <task-list user-only='true'></task-list>
                            </div>
                            <div class="tab-pane" role='tabpanel' id='all-tasks'>
                                <task-list></task-list>
                            </div>
                            <div class="tab-pane" role='tabpanel' id='campaigns'>
                                <campaign-list></campaign-list>
                            </div>
                            @else
                            <div class="tab-pane active" role='tabpanel' id='campaigns'>
                                <campaign-list></campaign-list>
                            </div>
                            @endcan

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
                @can('guests-denied')
                <div class="col-md-3" id="misc-container">
                    <recent-ideas-list></recent-ideas-list>
                    <recent-content-list></recent-content-list>
                </div>
                <div class="col-md-3">
                    <activity-feed></activity-feed>
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
                @endcan

            </div>
        </div>
    </div>

    @include('home.partials.invite_team_member_modal')
@stop

@section('scripts')
    <script src="{{ elixir('js/dashboard.js', null) }}"></script>
@stop