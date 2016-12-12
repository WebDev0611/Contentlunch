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
                <a href="/weekly/{{$prev_day_string}}" class="calendar-menu-navigator-link pull-left">
                    <i class="icon-arrow-right icon-flip-horizontal"></i>
                </a>
                <span class="calendar-menu-navigator-date">{{$weekly_display_string}}</span>
                <a href="/weekly/{{$next_day_string}}" class="calendar-menu-navigator-link pull-right">
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

       {!! $weekly_calendar !!}
    </div>
</div>

@include('calendar.filter')
@include('calendar.task')
@include('calendar.create')

@section('scripts')
<script src="/js/calendar.js"></script>
@stop

@include('calendar.modals')

<script type="text/template" id="calendar-item-template">
    <% 
    var _icon = 'primary icon-idea';
    if(type == 'task') _icon = 'secondary icon-arrange-mini'
    %>
      <i class="calendar-task-list-icon <%= _icon %>"></i>
      <%= title %>
      <div class="calendar-task-list-popover calendar-task-list-popover-bottom">
          <i class="calendar-task-list-icon secondary icon-arrange-mini"></i>
          <button class="calendar-task-list-popover-close">
              <i class="icon-remove"></i>
          </button>
          <h5 class="calendar-task-list-popover-title">
              Workout Secrets from Tinsel Town
          </h5>
          <p class="calendar-task-list-popover-text">
              Lorem ipsum dolor sit amet, consectetur adipisicing elit.
              Accusantium asperiores dolores excepturi natus neque officiis quibusdam
              rem, sunt voluptate voluptatum.
          </p>
          <div class="row">
              <div class="calendar-task-list-popover-info col-md-6">
                  Author
                  <strong>Jenny Hurley</strong>
              </div>
              <div class="calendar-task-list-popover-info col-md-6">
                  Due Date
                  <strong>30/12/2017</strong>
              </div>
          </div>
          <div class="calendar-task-list-popover-timeline">
              <span class="active">
                  <i class="icon-idea"></i>
              </span>
              <span class="active">
                  <i class="icon-edit-content"></i>
              </span>
              <span>
                  <i class="icon-content-alert"></i>
              </span>
              <span>
                  <i class="icon-connect"></i>
              </span>
          </div>
          <a href="#" class="button button-extend text-uppercase">Details</a>
      </div>
</script>

<script type="text/template" id="calendar-item-container">
<div class="calendar-schedule">
    <ul class="calendar-task-list">
    </ul>
</div>
</script>

<script>
var campaigns = {!! $campaigns !!};
var tasks = {!! $tasks !!};
</script>
@stop