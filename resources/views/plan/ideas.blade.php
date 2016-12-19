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
                        <img src="/assets/images/avatar.jpg" alt="#">
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


<script type="text/template" id="idea-template">
        <div class="plan-ideas-cell">
            <% var avatar = user.profile_image || '/images/avatar.jpg'; %>
            <img src="<%= avatar %>" alt="#" class="plan-ideas-img">
        </div>
        <div class="plan-ideas-cell">
            <h5 class="plan-ideas-title"><%= name %></h5>
            <span class="plan-ideas-text"><%= text %></span>
        </div>
        <div class="plan-ideas-cell">
        <!--

            <ul class="images-list">
                <li>
                    <img src="/images/avatar.jpg" alt="#">
                </li>
                <li>
                    <img src="/images/avatar.jpg" alt="#">
                </li>
                <li>
                    <img src="/images/avatar.jpg" alt="#">
                </li>
            </ul>
        -->

        </div>
        <div class="plan-ideas-cell">
            <span class="plan-ideas-text"><%= format_time_ago(created_at) %></span>
        </div>
        <div class="plan-ideas-cell">
            <span class="plan-ideas-text">UPDATED: <%= format_time_ago(updated_at) %></span>
        </div>
        <div class="plan-ideas-cell">
            <div class="plan-ideas-dropdown">
                <button type="button" class="button button-action" data-toggle="dropdown">
                    <i class="icon-add-circle"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-right">
                    <!--<li id="write-it-btn">
                        <a href="javascript:;">Write It</a>
                    </li> -->
                    <li id="park-it-btn">
                        <a href="javascript:;">Park It</a>
                    </li>
                  <li id="edit-it-btn">
                        <a href="javascript:;">Edit It</a>
                    </li>
                   <li id="unpark-it-btn">
                        <a href="javascript:;">Unpark It</a>
                    </li>
                    <!--
                    //should pre populate a create content form with the relevant data
                    <li id="socialize-it-btn">
                        <a href="javascript:;">Socialize It</a>
                    </li> -->
                </ul>
            </div>
        </div>
</script>

@stop

@section('scripts')
<script src="/js/ideas.js"></script>
@stop