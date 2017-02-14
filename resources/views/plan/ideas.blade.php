@extends('layouts.master')


@section('content')

<div class="workspace">
    <div class="panel clearfix">
        <div class="panel-main">

            <div class="panel-header">
                <ul class="panel-tabs text-center">
                    <li>
                        <a href="/plan">Topic Generator</a>
                    </li>
                    <li>
                        <a href="/plan/trends">Content Trends</a>
                    </li>
                    <!--<li>
                        <a href="/plan/prescription">Content Prescription</a>
                    </li> -->
                    <li class="active">
                        <a href="javascript:;" id="active-ideas-link">Active Ideas</a>
                    </li>
                    <li>
                        <a href="javascript:;" id="parked-ideas-link">Parked Ideas</a>
                    </li>
                </ul>
            </div>
            <input type="text" class="plan-panel-search" placeholder="Quick Search">
            <div id="idea-container">
            </div>

        </div>

        <!--
        <aside class="panel-sidebar">
            <div class="panel-header">
                <h4 class="panel-sidebar-title">Ideas activity feed</h4>
            </div>
            <div class="panel-container">
                <div class="plan-activity-box-container">
                    <div class="plan-activity-box-img">
                        <img src="/images/cl-avatar2.png" alt="#">
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
                        <img src="/assets/images/cl-avatar2.png" alt="#">
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
                </div>
            </div>
        </aside>
        -->
    </div>
</div>

@stop

@section('scripts')
<script src="/js/ideas.js"></script>
@stop