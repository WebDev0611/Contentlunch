@extends('layouts.master')

@section("styles")
    <style>
        .hide-over-10.order-container > :nth-child(n + 11) {
            display: none;
        }

        .hide-over-10 #showAllPanel{
            display: inline-table;
        }
        .title-cell{
            width: 400px;
        }

        .no-orders-message{
            margin: 20px 40px;
            display: none;
        }

        a.comments-link {
            color: #2481ff !important;
        }
    </style>
@stop

@section('content')
    <div class="workspace">

        <div class="panel clearfix">

            <div class="panel">
                @include('content.partials.dashboard.panel_tabs')

                @include('elements.freemium-alert')

                <content-orders-list></content-orders-list>

            </div>
            <aside class="panel-sidebar hide">
                <div class="panel-header">
                    <h4 class="panel-sidebar-title">Orders activity feed</h4>
                </div>
                <div class="panel-container">
                    {{--<div class="plan-activity-box-container">
                        <div class="plan-activity-box-img">
                            <img src="/images/avatar.jpg" alt="#">
                        </div>
                        <div class="plan-activity-box">
                        <span class="plan-activity-title">
                            <a href="#">Jane</a> commented on
                            <a href="#"> Write blog post</a> on
                            <a href="#">online banking</a>
                        </span>
                            <p class="plan-activity-text">
                                Suspendisse tincidunt eu lectus nec Suspen disse tincidunt eu lectus nec  vestibulum.
                                Etiam eget dolor...
                            </p>
                        </div>
                    </div>
                    <div class="plan-activity-box-container">
                        <div class="plan-activity-box-img">
                            <img src="/images/avatar.jpg" alt="#">
                        </div>
                        <div class="plan-activity-box">
                        <span class="plan-activity-title">
                            <a href="#">Jane</a> commented on
                            <a href="#"> Write blog post</a> on
                            <a href="#">online banking</a>
                        </span>
                            <p class="plan-activity-text">
                                Suspendisse tincidunt eu lectus nec Suspen disse tincidunt eu lectus nec  vestibulum.
                                Etiam eget dolor...
                            </p>
                        </div>
                    </div>
                    <div class="plan-activity-box-container">
                        <div class="plan-activity-box-icon">
                            <i class="icon-edit"></i>
                        </div>
                        <div class="plan-activity-box">
                        <span class="plan-activity-title">
                            <a href="#">Jane</a> commented on
                            <a href="#"> Write blog post</a> on
                            <a href="#">online banking</a>
                        </span>
                        </div>
                    </div>
                    <div class="plan-activity-box-container">
                        <div class="plan-activity-box-img">
                            <img src="/images/avatar.jpg" alt="#">
                        </div>
                        <div class="plan-activity-box">
                        <span class="plan-activity-title">
                            <a href="#">Jane</a> commented on
                            <a href="#"> Write blog post</a> on
                            <a href="#">online banking</a>
                        </span>
                            <p class="plan-activity-text">
                                Suspendisse tincidunt eu lectus nec Suspen disse tincidunt eu lectus nec  vestibulum.
                                Etiam eget dolor...
                            </p>
                            <div class="plan-activity-dropdown">
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
                    </div>--}}
                    <div class="alert alert-info alert-forms" role="alert"><p>No activity to show.</p></div>
                </div>
            </aside>
        </div>
    </div>

@stop