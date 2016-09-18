@extends('layouts.master')

@section('content')
<script>
var campaigns = {!! $campaigns !!};
</script>
<div class="workspace">
    <div class="calendar-container">
        <div class="calendar-navigation">
            <ul class="calendar-navigation-menu">
                <li>
                    <a href="/calendar">Calendar</a>
                </li>
                <li class="active">
                    <a href="#">Campaigns</a>
                </li>
            </ul>
        </div>
        <div class="calendar-menu">
            <div class="calendar-menu-select">
                <button class="calendar-menu-select-button" data-toggle="dropdown">
                    Calendar name
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li class="dropdown-header">Calendars</li>
                    <li>
                        <a href="#">Work Calendar</a>
                    </li>
                    <li>
                        <a href="#">Personal Calendar</a>
                    </li>
                    <li>
                        <a href="#">Family Calendar</a>
                    </li>
                </ul>
            </div>
            <div class="calender-menu-buttons-group">
                <div class="calendar-menu-dropdown">
                    <button class="calendar-menu-buttons" data-toggle="dropdown">
                        <i class="icon-calendar-view"></i>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                            <a href="#">Invite Collaborators</a>
                        </li>
                        <li>
                            <a href="#">Invite Guests</a>
                        </li>
                        <li>
                            <a href="#">Manage Access</a>
                        </li>
                    </ul>
                </div>
                <button class="calendar-menu-buttons">
                    <i class="icon-cone"></i>
                </button>
                <div class="calendar-menu-dropdown">
                    <button class="calendar-menu-buttons" data-toggle="dropdown">
                        <i class="icon-reverse-direction"></i>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li class="dropdown-header">Import</li>
                        <li>
                            <a href="#">From iCal</a>
                        </li>
                        <li>
                            <a href="#">From Google Calendar</a>
                        </li>
                        <li class="dropdown-header">Export</li>
                        <li>
                            <a href="#">To Google Calendar</a>
                        </li>
                        <li>
                            <a href="#">To iCal</a>
                        </li>
                        <li>
                            <a href="#">To PDF</a>
                        </li>
                        <li>
                            <a href="#">To Excel</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="calendar-timeline-zoom">
                <button class="calendar-timeline-zoom-button">
                    <i class="icon-zoom-out"></i>
                </button>
                <div class="calendar-timeline-zoom-bar">
                    <span class="calendar-timeline-zoom-bar-handle">
                        <i class="icon-double-caret"></i>
                    </span>
                </div>
                <button class="calendar-timeline-zoom-button">
                    <i class="icon-zoom-in"></i>
                </button>
            </div>
        </div>

        <div class="calendar-timeline-container">
        {!! $campaign_calendar !!}
        </div>
    </div>
</div>

<script type="text/template" id="campaign-template">
    <div class="calendar-timeline-task-item" style="left: 15px;">
        <span class="icon-double-caret left"></span>
        <%= title %>
        <span class="icon-double-caret right"></span>

        <div class="calendar-task-list-popover calendar-task-list-popover-bottom">
            <button class="calendar-task-list-popover-close">
                <i class="icon-remove"></i>
            </button>
            <h5 class="calendar-task-list-popover-title">
                Print Campaign: UN
                <span class="calendar-task-list-popover-title-border"></span>
            </h5>
            <div class="calendar-task-list-popover-author">
                <img src="/images/avatar.jpg" alt="#">
                <span class="text-uppercase">Author</span>
                <strong>Storm Trooper</strong>
            </div>
            <div class="row">
                <div class="calendar-task-list-popover-info col-md-6">
                    Starts
                    <strong><%= start_date %></strong>
                </div>
                <div class="calendar-task-list-popover-info col-md-6">
                    Due Date
                    <strong><%= end_date %></strong>
                </div>
            </div>
            <p class="calendar-task-list-popover-info">
                Content
                <strong>Print Ad Washington Post</strong>
                <strong>Print Ad New York Times</strong>
                <strong>Print Ad Metro</strong>
                <a href="#" class="button button-outline-secondary button-micro">+5 More</a>
            </p>
            <a href="#" class="button button-extend text-uppercase">Details</a>
        </div>
    </div>
</script>
@stop

@section('scripts')
<script src="/js/campaign-calendar.js"></script>
@stop