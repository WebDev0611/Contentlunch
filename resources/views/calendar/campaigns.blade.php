@extends('layouts.master')

@section('content')
<script>
var campaigns = {!! $campaigns !!};
</script>
<div class="workspace">
    <div class="calendar-container">

        @include('elements.freemium-alert', ['restriction' => 'create only one calendar'])

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

        {{--
        <div class="calendar-menu">
            <div class="calendar-menu-select">
                <button class="calendar-menu-select-button" data-toggle="dropdown">
                    Calendar name
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-left">
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
        --}}
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
                <%= title %>
                <span class="calendar-task-list-popover-title-border"></span>
            </h5>
            <p class="calendar-task-list-popover-text">
              <%= explanation %>
          </p>
            <div class="calendar-task-list-popover-author">
                  <span class="text-uppercase">Collaborators</span>

                  <% if (typeof(user_image) !== "undefined" && user_image !== null) { %>
                    <img src="<%= user_image %>" width="50" class="popover-user-image">
                  <% } else { %>
                    <img src="/images/cl-avatar2.png" alt="#">
                  <%  } %>
                  <strong><%= author %></strong>
            </div>
            <div class="row">
                <div class="calendar-task-list-popover-info col-md-6">
                    Starts
                    <strong><%= start_date %></strong>
                </div>
                <div class="calendar-task-list-popover-info col-md-6">
                    End Date
                    <strong><%= end_date %></strong>
                </div>
            </div>
            <p class="calendar-task-list-popover-info">
                Content
                <% if (typeof(contents) !== "undefined" && contents !== null && contents.length) { %>
                    <ul>
                    <% contents.forEach(function (content) { %>
                        <li><strong><%= content.title %></strong></li>
                    <% }); %>
                    </ul>
                <% } else { %>
                    <div class="clearfix"></div>
                    <i>Currently no content</i>
                <% } %>
            </p>
            <a href="<%= details_url %>" class="button button-extend text-uppercase">Details</a>
        </div>
    </div>
</script>
@stop

@section('scripts')
<script src="{{ elixir('js/campaign-calendar.js', null) }}"></script>
<script>
    //tasks
    $('#add-task-button').click(function() {
        add_task(addTaskCallback);
    });

    function addTaskCallback(task) {
        $('#addTaskModal').modal('hide');
    }
</script>
@stop