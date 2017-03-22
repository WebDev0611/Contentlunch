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
                <a href="/calendar/{{$cal->id}}/{{$default_year}}/{{$prev_month}}" class="calendar-menu-navigator-link pull-left">
                    <i class="icon-arrow-right icon-flip-horizontal"></i>
                </a>
                <span class="calendar-menu-navigator-date">{{$default_month}} {{$default_year}}</span>
                <a href="/calendar/{{$cal->id}}/{{$default_year}}/{{$next_month}}" class="calendar-menu-navigator-link pull-right">
                    <i class="icon-arrow-right"></i>
                </a>
            </div>
            <div class="calendar-menu-select">
                <button class="calendar-menu-select-button" data-toggle="dropdown">
                    {{$cal->name}}
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li class="dropdown-header">Calendars</li>
                    @foreach($my as $myCalendar)
                        <li>
                            <a href="{{route('calendarMonthly', $myCalendar->id)}}">{{$myCalendar->name}}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            @include('calendar.menu')
        </div>

       {!! $calendar !!}
    </div>
</div>

<div id="calendar-loading-gif">
    <img src="{{asset('images/loading.gif')}}" alt="">
    <p>Loading your calendar content...</p>
</div>

@include('calendar.filter')
@include('calendar.create')
@include('calendar.contentmodal')

@include('plan.createmodal')
@stop

@section('styles')
    <link rel="stylesheet" href="/css/plugins/fastselect/fastselect.min.css">
@stop

@section('scripts')
<script type="text/template" id="calendar-item-template">
    <%
    var _icon = 'primary icon-idea';
    if(type == 'task') _icon = 'secondary icon-arrange-mini'
    if(type == 'idea') _icon = 'purple icon-idea'
    if(type == 'content') _icon = type_class
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
                    <img src="<%= user_image %>" width="50" class="popover-user-image">
                  <% } %>
                  <strong><%= author %></strong>
              </div>
              <% if (typeof(due) !== "undefined" && due !== null) { %>
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
              <% if (type == 'idea') { %>
              <div class="calendar-task-list-popover-info col-md-6">
                  Status
                  <strong class='upper'><%= status %></strong>
              </div>
              <% } %>
               <% if (type == 'task' && typeof(assigned_to) !== "undefined" && assigned_to !== null) { %>
              <div class="calendar-task-list-popover-author col-md-6">
                <span class="text-uppercase">Assigned to</span>
                    <%  _.each(assigned_to, function(usr){ %>
                        <strong><%= usr %></strong>
                    <% }); %>
              </div>
              <% } %>
              <% if (type == 'task' && typeof(contents) !== "undefined" && contents !== null && contents.length > 0) { %>
              <div class="calendar-task-list-popover-contents col-md-6">
                  <span class="text-uppercase">Belongs to</span>
                  <%  _.each(contents, function(content){ %>
                  <a href="/edit/<%= content.id %>"><button class="btn btn-sm btn-primary"><%= content.title %></button></a>
                  <% }); %>
              </div>
              <% } %>
          </div>
          <% if (type != 'task') { %>
          <div class="calendar-task-list-popover-timeline">
              <span class="active">
                  <i class="icon-idea"></i>
              </span>
              <span <% if (type == 'content' && (content_status == 'written' || content_status == 'ready_published' || content_status == 'published')) { %> class="active" <% } %>>
                  <i class="icon-edit-content"></i>
              </span>
              <span <% if (type == 'content' && (content_status == 'ready_published' || content_status == 'published')) { %> class="active" <% } %>>
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
                  <a href="javascript:;" class="tool-add-task">Add Task</a>
                </li>
                 <li>
                     <a href="javascript:;" class="tool-add-idea" >Add Idea</a>
                 </li>
                 <li>
                     <a href="javascript:;" class="tool-add-content">Add Content</a>
                 </li>

              </ul>
        </div>
    </div>
</script>
<script>
var campaigns = {!! $campaigns !!};
var calendar = {!! $cal !!};
var my = {!! $my !!};
</script>
<script src="/js/calendar.js"></script>
<script src="/js/calendar-helpers.js"></script>
@stop