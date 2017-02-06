@extends('layouts.master')

@section('content')

<div class="workspace">
    <div class="calendar-container">

        <div class="calendar-navigation">
            <ul class="calendar-navigation-menu">
                <li class="active">
                    <a href="#">Calendar</a>
                </li>
                <li>
                    <a href="/campaigns">Campaigns</a>
                </li>
            </ul>
        </div>
        <div class="calendar-menu">
            <div class="calendar-menu-navigator">
                <a href="/calendar/{{$default_year}}/{{$prev_month}}" class="calendar-menu-navigator-link pull-left">
                    <i class="icon-arrow-right icon-flip-horizontal"></i>
                </a>
                <span class="calendar-menu-navigator-date">{{$default_month}} {{$default_year}}</span>
                <a href="/calendar/{{$default_year}}/{{$next_month}}" class="calendar-menu-navigator-link pull-right">
                    <i class="icon-arrow-right"></i>
                </a>
            </div>
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
            @include('calendar.menu')
        </div>

       {!! $calendar !!}
    </div>
</div>

@include('calendar.filter')
@include('calendar.create')

<script type="text/template" id="calendar-item-template">
    <% 
    var _icon = 'primary icon-idea';
    if(type == 'task') _icon = 'secondary icon-arrange-mini'
    if(type == 'content') _icon = 'primary icon-content-alert'
    if(type == 'idea') _icon = 'purple icon-idea'
    %>
      <i class="calendar-task-list-icon <%= _icon %>"></i>
      <%= title %>
      <div class="calendar-task-list-popover calendar-task-list-popover-bottom">
          <i class="calendar-task-list-icon <%= _icon %>"></i>
          <button class="calendar-task-list-popover-close">
              <i class="icon-remove"></i>
          </button>
          <h5 class="calendar-task-list-popover-title">
              <%= title %>
          </h5>
          <p class="calendar-task-list-popover-text">
              <%= explanation %>
          </p>
          <div class="row">
              <div class="calendar-task-list-popover-info col-md-6">
                  Author
                  <% if (typeof(user_image) !== "undefined" && user_image !== null) { %>
                    <img src="user_image" width="50">
                  <% } %>
                  <strong><%= author %></strong>
              </div>
              <% if (type == 'task') { %>
              <div class="calendar-task-list-popover-info col-md-6">
                  Due Date
                  <strong><%= due %></strong>
              </div>
               <% } %>
               <% if (type == 'content') { %>
              <div class="calendar-task-list-popover-info col-md-6">
                  Status
                  <strong class='upper'><%= content_status_text %></strong>
              </div>
               <% } %>
          </div>
          <% if (type != 'task') { %>
          <div class="calendar-task-list-popover-timeline">
              <span class="active">
                  <i class="icon-idea"></i>
              </span>
              <span <% if (type == 'content' && (content_status == 'written' || content_status == 'ready_published')) { %> class="active" <% } %>>
                  <i class="icon-edit-content"></i>
              </span>
              <span <% if (type == 'content' && content_status == 'ready_published') { %> class="active" <% } %>>
                  <i class="icon-content-alert"></i>
              </span>
              <span <% if (type == 'content' && content_status == 'published') { %> class="active" <% } %>>
                  <i class="icon-connect"></i>
              </span>
          </div>
          <% } %>

          <a href="<%= details_url %>" class="button button-extend text-uppercase">Details</a>

      </div>
</script>

<script type="text/template" id="calendar-item-container">
<div class="calendar-schedule">
    <ul class="calendar-task-list t-task">
    </ul>
    <ul class="calendar-task-list t-idea">
    </ul>
    <ul class="calendar-task-list t-content">
    </ul>
</div>
</script>

<script type="text/template" id="calendar-dropdown-template">
     <div class="calendar-schedule-dropdown-wrapper" style="display:none">
        <div class="calendar-schedule-dropdown">
            <button type="button" class="button button-action" data-toggle="dropdown">
                <i class="icon-add-circle"></i>
            </button>
             <ul class="dropdown-menu dropdown-menu-right">
                <li class="dropdown-header important date-popup-label"></li>
                <li>
                  <a href="#" data-toggle="modal" data-target="#addIdeaCalendar">Add Idea</a>
                </li>
                <li>
                  <a href="#" data-toggle="modal" data-target="#addContentCalendar">Add Content</a>
                </li>
                <li>
                  <a href="javascript:;" class="tool-add-task">Add Task</a>
                </li>
              </ul>
        </div>
    </div>
</script>


@section('scripts')
<script>
var campaigns = {!! $campaigns !!};
var tasks = {!! $tasks !!};
</script>
<script src="/js/calendar.js"></script>
@stop

@include('calendar.modals')

@stop
