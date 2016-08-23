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
       <!--
   <table class="calendar">
            <thead class="calendar-week">
                <tr>
                    <th disabled></th>
                    <th>Sunday, 1 Mar</th>
                    <th>Monday, 2 Mar</th>
                    <th>Tuesday, 3 Mar</th>
                    <th>Wednesday, 4 Mar</th>
                    <th>Thursday, 5 Mar</th>
                    <th>Friday, 6 Mar</th>
                    <th>Saturday, 7 Mar</th>
                </tr>
            </thead>
            <tbody class="calendar-week-hours">
                <tr>
                    <td disabled>
                        10 AM
                    </td>
                    <td>
                        <div class="calendar-schedule">
                            <ul class="calendar-task-list">
                                <li>
                                    <i class="calendar-task-list-icon primary icon-idea"></i>
                                    Content mix: post 3 blogs...
                                </li>
                                <li>
                                    <i class="calendar-task-list-icon secondary icon-arrange-mini"></i>
                                    Post 16 social postings
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <div class="calendar-schedule">
                            <ul class="calendar-task-list">
                                <li>
                                    <i class="calendar-task-list-icon primary icon-idea"></i>
                                    Content mix: post 3 blogs...
                                </li>
                                <li>
                                    <i class="calendar-task-list-icon secondary icon-arrange-mini"></i>
                                    Post 16 social postings
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td disabled>
                        11 AM
                    </td>
                    <td>
                        <div class="calendar-schedule">
                            <ul class="calendar-task-list">
                                <li>
                                    <i class="calendar-task-list-icon primary icon-idea"></i>
                                    Content mix: post 3 blogs...
                                </li>
                                <li>
                                    <i class="calendar-task-list-icon secondary icon-arrange-mini"></i>
                                    Post 16 social postings
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <div class="calendar-schedule-dropdown-wrapper">
                            <div class="calendar-schedule-dropdown">
                                <button type="button" class="button button-action" data-toggle="dropdown">
                                    <i class="icon-add-circle"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li class="dropdown-header important">Wed, Mar 4, 2016, 01 PM</li>
                                    <li>
                                        <a href="#">Add Idea</a>
                                    </li>
                                    <li>
                                        <a href="#">Add Content</a>
                                    </li>
                                    <li>
                                        <a href="#">Add Task</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td disabled>
                        12 PM
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <div class="calendar-schedule">
                            <ul class="calendar-task-list">
                                <li>
                                    <i class="calendar-task-list-icon primary icon-idea"></i>
                                    Content mix: post 3 blogs...
                                </li>
                                <li>
                                    <i class="calendar-task-list-icon secondary icon-arrange-mini"></i>
                                    Post 16 social postings
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td disabled>
                        01 PM
                    </td>
                    <td></td>
                    <td>
                        <div class="calendar-schedule">
                            <ul class="calendar-task-list">
                                <li>
                                    <i class="calendar-task-list-icon primary icon-idea"></i>
                                    Content mix: post 3 blogs...
                                </li>
                                <li>
                                    <i class="calendar-task-list-icon secondary icon-arrange-mini"></i>
                                    Post 16 social postings
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td disabled>
                        02 PM
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <div class="calendar-schedule">
                            <ul class="calendar-task-list">
                                <li>
                                    <i class="calendar-task-list-icon primary icon-idea"></i>
                                    Content mix: post 3 blogs...
                                </li>
                                <li>
                                    <i class="calendar-task-list-icon secondary icon-arrange-mini"></i>
                                    Post 16 social postings
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td disabled>
                        03 PM
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td disabled>
                        04 PM
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td disabled>
                        05 PM
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td disabled>
                        06 PM
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <div class="calendar-schedule">
                            <ul class="calendar-task-list">
                                <li>
                                    <i class="calendar-task-list-icon primary icon-idea"></i>
                                    Content mix: post 3 blogs...
                                </li>
                                <li>
                                    <i class="calendar-task-list-icon secondary icon-arrange-mini"></i>
                                    Post 16 social postings
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td disabled>
                        07 PM
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td disabled>
                        08 PM
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td disabled>
                        09 PM
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td disabled>
                        10 PM
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <div class="calendar-schedule">
                            <ul class="calendar-task-list">
                                <li>
                                    <i class="calendar-task-list-icon primary icon-idea"></i>
                                    Content mix: post 3 blogs...
                                </li>
                                <li>
                                    <i class="calendar-task-list-icon secondary icon-arrange-mini"></i>
                                    Post 16 social postings
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
-->
    </div>
</div>

@include('calendar.filter')
@include('calendar.task')
@include('calendar.create')


@stop

@section('scripts')
<script src="/js/calendar.js"></script>
@stop

@include('calendar.modals')