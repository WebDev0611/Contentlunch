@extends('2016.layout.master')

@section('content')

<div class="workspace">
    <div class="calendar-container">
        <div class="calendar-navigation">
            <ul class="calendar-navigation-menu">
                <li class="active">
                    <a href="#">Calendar</a>
                </li>
                <li>
                    <a href="#">Campaigns</a>
                </li>
            </ul>
        </div>
        <div class="calendar-menu">
            <div class="calendar-menu-navigator">
                <a href="#" class="calendar-menu-navigator-link pull-left">
                    <i class="icon-arrow-right icon-flip-horizontal"></i>
                </a>
                <span class="calendar-menu-navigator-date">March 2016</span>
                <a href="#" class="calendar-menu-navigator-link pull-right">
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
            <div class="calender-menu-buttons-group">
                <button class="calendar-menu-buttons">
                    <i class="icon-calendar-add"></i>
                </button>
                <button class="calendar-menu-buttons">
                    <i class="icon-calendar-view"></i>
                </button>
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
            <div class="calendar-menu-switch">
                <button type="button" class="button button-secondary button-small">Today</button>
                <div class="btn-group">
                    <button type="button" class="button button-switches button-small active">Month</button>
                    <button type="button" class="button button-switches button-small">Week</button>
                    <button type="button" class="button button-switches button-small">Day</button>
                </div>
            </div>
        </div>
        <table class="calendar">
            <thead class="calendar-month">
                <tr>
                    <th>Sunday</th>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                    <th>Friday</th>
                    <th>Saturday</th>
                </tr>
            </thead>
            <tbody class="calendar-month-days">
                <tr>
                    <td disabled></td>
                    <td disabled></td>
                    <td>
                        <div class="calendar-schedule">
                            <ul class="calendar-task-list">
                                <li class="active">
                                    <div class="calendar-task-list-popover calendar-task-list-popover-bottom open">
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
                                    <i class="calendar-task-list-icon primary icon-idea"></i>
                                    Content mix: post 3 blogs...
                                </li>
                            </ul>
                        </div>
                        <time class="calendar-month-date">1</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">2</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">3</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">4</time>
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
                                <li>+ 3 more - SHOW</li>
                            </ul>
                        </div>
                        <time class="calendar-month-date">5</time>
                    </td>
                </tr>
                <tr>
                    <td>
                        <time class="calendar-month-date">6</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">7</time>
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
                        <time class="calendar-month-date">8</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">9</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">10</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">11</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">12</time>
                    </td>
                </tr>
                <tr>
                    <td>
                        <time class="calendar-month-date">13</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">14</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">15</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">16</time>
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
                        <time class="calendar-month-date">17</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">18</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">19</time>
                    </td>
                </tr>
                <tr>
                    <td>
                        <time class="calendar-month-date">20</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">21</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">22</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">23</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">24</time>
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
                        <time class="calendar-month-date">25</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">26</time>
                    </td>
                </tr>
                <tr>
                    <td>
                        <time class="calendar-month-date">27</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">28</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">29</time>
                    </td>
                    <td>
                        <time class="calendar-month-date">30</time>
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
                        <time class="calendar-month-date">31</time>
                    </td>
                    <td disabled></td>
                    <td disabled></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


@stop