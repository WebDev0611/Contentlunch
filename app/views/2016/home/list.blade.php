@extends('2016.layout.master')

@section('content')
<div class="workspace">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <div class="dashboard-notification-box">
                    <span class="dashboard-notification-box-count">
                        <i class="icon-checklist"></i>
                        35
                    </span>
                    <span>Incomplete <br> Tasks</span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="dashboard-notification-box">
                    <span class="dashboard-notification-box-count">
                        <i class="icon-idea"></i>
                        5
                    </span>
                    <span>New Ideas<br> Posted</span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="dashboard-notification-box">
                    <span class="dashboard-notification-box-count">
                        <i class="icon-navigation-dashboard"></i>
                        5
                    </span>
                    <span>New Content <br>Pieces Posted</span>
                </div>
            </div>
            <div class="col-md-6 text-right">
                <div class="dashboard-layout-container">
                    <span>Writer / Editor Layout</span>
                    <ul class="dashboard-layout">
                        <li>
                            <a href="/2016/home">
                                <i class="icon-chart"></i>
                            </a>
                        </li>
                        <li class="active">
                            <a href="javascript:;">
                                <i class="icon-itemlist"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-header">
                        <ul class="panel-tabs spacing">
                            <li class="active">
                                <a href="/#/">My tasks</a>
                            </li>
                            <li>
                                <a href="/#/">All tasks</a>
                            </li>
                            <li>
                                <a href="/#/">Campaigns</a>
                            </li>
                            <li>
                                <a href="/#/">Recently Viewed</a>
                            </li>
                        </ul>
                    </div>
                    <div class="dashboard-tasks-container">
                        <div class="dashboard-tasks-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-tasks-cell">
                            <h5 class="dashboard-tasks-title">
                                Content mix: post 3 blogs, 16 social postings, 1 book per month
                            </h5>
                            <span class="dashboard-tasks-text">
                                Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...
                            </span>
                            <ul class="dashboard-tasks-list">
                                <li>DUE IN: <strong>2 DAYS</strong></li>
                                <li>
                                    STAGE:
                                    <i class="dashboard-tasks-list-icon primary icon-idea"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-content"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-alert"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-share"></i>
                                </li>
                                <li>
                                    <a href="#"><strong>Edit Content</strong></a>
                                </li>
                            </ul>
                        </div>
                        <div class="dashboard-tasks-cell">
                            <span class="dashboard-tasks-text small active">JUST NOW</span>
                        </div>
                    </div>
                    <div class="dashboard-tasks-container">
                        <div class="dashboard-tasks-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-tasks-cell">
                            <h5 class="dashboard-tasks-title">
                                Content mix: post 3 blogs, 16 social postings, 1 book per month
                            </h5>
                            <span class="dashboard-tasks-text">
                                Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...
                            </span>
                            <ul class="dashboard-tasks-list">
                                <li>DUE IN: <strong>2 DAYS</strong></li>
                                <li>
                                    STAGE:
                                    <i class="dashboard-tasks-list-icon primary icon-idea"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-content"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-alert"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-share"></i>
                                </li>
                                <li>
                                    <a href="#">
                                        <strong>Write Content</strong>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="dashboard-tasks-cell">
                            <span class="dashboard-tasks-text small">3 DAYS AGO</span>
                        </div>
                    </div>
                    <div class="dashboard-tasks-container">
                        <div class="dashboard-tasks-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-tasks-cell">
                            <h5 class="dashboard-tasks-title">
                                Content mix: post 3 blogs, 16 social postings, 1 book per month
                            </h5>
                            <span class="dashboard-tasks-text">
                                Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...
                            </span>
                            <ul class="dashboard-tasks-list">
                                <li>DUE IN: <strong>2 DAYS</strong></li>
                                <li>
                                    STAGE:
                                    <i class="dashboard-tasks-list-icon primary icon-idea"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-content"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-alert"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-share"></i>
                                </li>
                                <li>
                                    <a href="#">
                                        <strong>Write Content</strong>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="dashboard-tasks-cell">
                            <span class="dashboard-tasks-text small">3 DAYS AGO</span>
                        </div>
                    </div>
                    <div class="dashboard-tasks-container">
                        <div class="dashboard-tasks-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-tasks-cell">
                            <h5 class="dashboard-tasks-title">
                                Content mix: post 3 blogs, 16 social postings, 1 book per month
                            </h5>
                            <span class="dashboard-tasks-text">
                                Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...
                            </span>
                            <ul class="dashboard-tasks-list">
                                <li>DUE IN: <strong>2 DAYS</strong></li>
                                <li>
                                    STAGE:
                                    <i class="dashboard-tasks-list-icon primary icon-idea"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-content"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-alert"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-share"></i>
                                </li>
                                <li>
                                    <a href="#">
                                        <strong>Write Content</strong>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="dashboard-tasks-cell">
                            <span class="dashboard-tasks-text small">3 DAYS AGO</span>
                        </div>
                    </div>
                    <div class="dashboard-tasks-container">
                        <div class="dashboard-tasks-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-tasks-cell">
                            <h5 class="dashboard-tasks-title">
                                Content mix: post 3 blogs, 16 social postings, 1 book per month
                            </h5>
                            <span class="dashboard-tasks-text">
                                Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...
                            </span>
                            <ul class="dashboard-tasks-list">
                                <li>DUE IN: <strong>2 DAYS</strong></li>
                                <li>
                                    STAGE:
                                    <i class="dashboard-tasks-list-icon primary icon-idea"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-content"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-alert"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-share"></i>
                                </li>
                                <li>
                                    <a href="#">
                                        <strong>Write Content</strong>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="dashboard-tasks-cell">
                            <span class="dashboard-tasks-text small">3 DAYS AGO</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel">
                    <div class="panel-header">
                        <h4 class="panel-sidebar-title-secondary">Activity Feed</h4>
                    </div>
                    <div class="panel-container">
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
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel">
                    <div class="panel-header">
                        <h4 class="panel-sidebar-title-secondary">
                            Recent Ideas
                            <a href="/2016/plan/ideas">
                                See All
                                <i class="icon-arrow-right"></i>
                            </a>
                        </h4>
                    </div>
                    <div class="dashboard-ideas-container">
                        <div class="dashboard-ideas-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-ideas-cell">
                            <p class="dashboard-ideas-text">Content mix: post 16 social postings</p>
                            <span class="dashboard-ideas-text small">3 Days Ago</span>
                        </div>
                    </div>
                    <div class="dashboard-ideas-container">
                        <div class="dashboard-ideas-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-ideas-cell">
                            <p class="dashboard-ideas-text">Content mix: post 16 social postings</p>
                            <span class="dashboard-ideas-text small">3 Days Ago</span>
                        </div>
                    </div>
                    <div class="dashboard-ideas-container">
                        <div class="dashboard-ideas-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-ideas-cell">
                            <p class="dashboard-ideas-text">Content mix: post 16 social</p>
                            <span class="dashboard-ideas-text small">3 Days Ago</span>
                        </div>
                        <div class="dashboard-ideas-cell">
                            <div class="dashboard-ideas-dropdown">
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
                <div class="panel">
                    <div class="panel-header">
                        <h4 class="panel-sidebar-title-secondary">
                            Team Members
                            <a href="#">
                                INVITE
                                <i class="icon-edit-user"></i>
                            </a>
                        </h4>
                    </div>
                    <div class="dashboard-members-container">
                        <div class="dashboard-ideas-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-members-cell">
                            <p class="dashboard-ideas-text">Jason Simmons</p>
                            <span class="dashboard-members-text small">jasonsimm@google.com</span>
                        </div>
                        <div class="dashboard-members-cell">
                            <span class="dashboard-ideas-text small">
                                <i class="icon-checklist"></i>
                                35
                            </span>
                        </div>
                    </div>
                    <div class="dashboard-members-container">
                        <div class="dashboard-ideas-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-members-cell">
                            <p class="dashboard-ideas-text">Jason Simmons</p>
                            <span class="dashboard-members-text small">jasonsimm@google.com</span>
                        </div>
                        <div class="dashboard-members-cell">
                            <span class="dashboard-ideas-text small">
                                <i class="icon-checklist"></i>
                                35
                            </span>
                        </div>
                    </div>
                    <div class="dashboard-members-container">
                        <div class="dashboard-ideas-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-members-cell">
                            <p class="dashboard-ideas-text">Jason Simmons</p>
                            <span class="dashboard-members-text small">jasonsimm@google.com</span>
                        </div>
                        <div class="dashboard-members-cell">
                            <span class="dashboard-ideas-text small">
                                <i class="icon-checklist"></i>
                                35
                            </span>
                        </div>
                    </div>
                    <div class="dashboard-members-container">
                        <div class="dashboard-members-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-members-cell">
                            <p class="dashboard-ideas-text">Jason Simmons</p>
                            <span class="dashboard-members-text small">jasonsimm@google.com</span>
                        </div>
                        <div class="dashboard-members-cell">
                            <span class="dashboard-ideas-text small">
                                <i class="icon-checklist"></i>
                                35
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- //OTHER MODULES - copy from Dash 2 -->
<!--
<div class="workspace">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                <div class="dashboard-notification-box">
                    <span class="dashboard-notification-box-count">
                        <i class="icon-checklist"></i>
                        35
                    </span>
                    <span>Incomplete <br> Tasks</span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="dashboard-notification-box">
                    <span class="dashboard-notification-box-count">
                        <i class="icon-idea"></i>
                        5
                    </span>
                    <span>New Ideas<br> Posted</span>
                </div>
            </div>
            <div class="col-md-2">
                <div class="dashboard-notification-box">
                    <span class="dashboard-notification-box-count">
                        <i class="icon-navigation-dashboard"></i>
                        5
                    </span>
                    <span>New Content <br>Pieces Posted</span>
                </div>
            </div>
            <div class="col-md-6 text-right">
                <div class="dashboard-layout-container">
                    <span>Writer / Editor Layout</span>
                    <ul class="dashboard-layout">
                        <li>
                            <a href="#">
                                <i class="icon-chart"></i>
                            </a>
                        </li>
                        <li class="active">
                            <a href="#">
                                <i class="icon-itemlist"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-header">
                        <ul class="panel-tabs spacing">
                            <li class="active">
                                <a href="/#/">My tasks</a>
                            </li>
                            <li>
                                <a href="/#/">All tasks</a>
                            </li>
                            <li>
                                <a href="/#/">Campaigns</a>
                            </li>
                            <li>
                                <a href="/#/">Recently Viewed</a>
                            </li>
                        </ul>
                    </div>
                    <div class="dashboard-tasks-container">
                        <div class="dashboard-tasks-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-tasks-cell">
                            <h5 class="dashboard-tasks-title">
                                Content mix: post 3 blogs, 16 social postings, 1 book per month
                            </h5>
                            <span class="dashboard-tasks-text">
                                Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...
                            </span>
                            <ul class="dashboard-tasks-list">
                                <li>DUE IN: <strong>2 DAYS</strong></li>
                                <li>
                                    STAGE:
                                    <i class="dashboard-tasks-list-icon primary icon-idea"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-content"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-alert"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-share"></i>
                                </li>
                                <li>
                                    <a href="#"><strong>Edit Content</strong></a>
                                </li>
                            </ul>
                        </div>
                        <div class="dashboard-tasks-cell">
                            <span class="dashboard-tasks-text small active">JUST NOW</span>
                        </div>
                    </div>
                    <div class="dashboard-tasks-container">
                        <div class="dashboard-tasks-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-tasks-cell">
                            <h5 class="dashboard-tasks-title">
                                Content mix: post 3 blogs, 16 social postings, 1 book per month
                            </h5>
                            <span class="dashboard-tasks-text">
                                Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...
                            </span>
                            <ul class="dashboard-tasks-list">
                                <li>DUE IN: <strong>2 DAYS</strong></li>
                                <li>
                                    STAGE:
                                    <i class="dashboard-tasks-list-icon primary icon-idea"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-content"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-alert"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-share"></i>
                                </li>
                                <li>
                                    <a href="#">
                                        <strong>Write Content</strong>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="dashboard-tasks-cell">
                            <span class="dashboard-tasks-text small">3 DAYS AGO</span>
                        </div>
                    </div>
                    <div class="dashboard-tasks-container">
                        <div class="dashboard-tasks-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-tasks-cell">
                            <h5 class="dashboard-tasks-title">
                                Content mix: post 3 blogs, 16 social postings, 1 book per month
                            </h5>
                            <span class="dashboard-tasks-text">
                                Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...
                            </span>
                            <ul class="dashboard-tasks-list">
                                <li>DUE IN: <strong>2 DAYS</strong></li>
                                <li>
                                    STAGE:
                                    <i class="dashboard-tasks-list-icon primary icon-idea"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-content"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-alert"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-share"></i>
                                </li>
                                <li>
                                    <a href="#">
                                        <strong>Write Content</strong>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="dashboard-tasks-cell">
                            <span class="dashboard-tasks-text small">3 DAYS AGO</span>
                        </div>
                    </div>
                    <div class="dashboard-tasks-container">
                        <div class="dashboard-tasks-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-tasks-cell">
                            <h5 class="dashboard-tasks-title">
                                Content mix: post 3 blogs, 16 social postings, 1 book per month
                            </h5>
                            <span class="dashboard-tasks-text">
                                Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...
                            </span>
                            <ul class="dashboard-tasks-list">
                                <li>DUE IN: <strong>2 DAYS</strong></li>
                                <li>
                                    STAGE:
                                    <i class="dashboard-tasks-list-icon primary icon-idea"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-content"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-alert"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-share"></i>
                                </li>
                                <li>
                                    <a href="#">
                                        <strong>Write Content</strong>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="dashboard-tasks-cell">
                            <span class="dashboard-tasks-text small">3 DAYS AGO</span>
                        </div>
                    </div>
                    <div class="dashboard-tasks-container">
                        <div class="dashboard-tasks-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-tasks-cell">
                            <h5 class="dashboard-tasks-title">
                                Content mix: post 3 blogs, 16 social postings, 1 book per month
                            </h5>
                            <span class="dashboard-tasks-text">
                                Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...
                            </span>
                            <ul class="dashboard-tasks-list">
                                <li>DUE IN: <strong>2 DAYS</strong></li>
                                <li>
                                    STAGE:
                                    <i class="dashboard-tasks-list-icon primary icon-idea"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-content"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-alert"></i>
                                    <i class="dashboard-tasks-list-icon tertiary icon-share"></i>
                                </li>
                                <li>
                                    <a href="#">
                                        <strong>Write Content</strong>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="dashboard-tasks-cell">
                            <span class="dashboard-tasks-text small">3 DAYS AGO</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel max-height">
                    <div class="panel-header">
                        <h4 class="panel-sidebar-title-secondary">
                            Recent Ideas
                            <a href="#">
                                See All
                                <i class="icon-arrow-right"></i>
                            </a>
                        </h4>
                    </div>
                    <div class="dashboard-ideas-container">
                        <div class="dashboard-ideas-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-ideas-cell">
                            <p class="dashboard-ideas-text">Content mix: post 16 social postings</p>
                            <span class="dashboard-ideas-text small">3 Days Ago</span>
                        </div>
                    </div>
                    <div class="dashboard-ideas-container">
                        <div class="dashboard-ideas-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-ideas-cell">
                            <p class="dashboard-ideas-text">Content mix: post 16 social postings</p>
                            <span class="dashboard-ideas-text small">3 Days Ago</span>
                        </div>
                    </div>
                    <div class="dashboard-ideas-container">
                        <div class="dashboard-ideas-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-ideas-cell">
                            <p class="dashboard-ideas-text">Content mix: post 16 social</p>
                            <span class="dashboard-ideas-text small">3 Days Ago</span>
                        </div>
                        <div class="dashboard-ideas-cell">
                            <div class="dashboard-ideas-dropdown">
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
                <div class="panel max-height">
                    <div class="panel-header">
                        <h4 class="panel-sidebar-title-secondary">
                            Campaigns
                            <a href="#">
                                See All
                                <i class="icon-arrow-right"></i>
                            </a>
                        </h4>
                    </div>
                    <div class="dashboard-campaigns">
                        <div class="dashboard-campaigns-cell">
                            <ul class="dashboard-campaign-images-list">
                                <li>
                                    <img src="/assets/images/avatar.jpg" alt="#">
                                </li>
                                <li>
                                    <img src="/assets/images/avatar.jpg" alt="#">
                                </li>
                            </ul>
                        </div>
                        <div class="dashboard-campaigns-cell">
                            <p class="dashboard-campaigns-title">
                                16 social postings on woman rights and movements around the world
                            </p>
                            <span class="dashboard-campaigns-text small">
                                NEXT TASK: <strong>LAUNCH</strong>
                            </span>
                        </div>
                    </div>
                    <div class="dashboard-campaigns">
                        <div class="dashboard-campaigns-cell">
                            <ul class="dashboard-campaign-images-list">
                                <li>
                                    <img src="/assets/images/avatar.jpg" alt="#">
                                </li>
                                <li>
                                    <img src="/assets/images/avatar.jpg" alt="#">
                                </li>
                            </ul>
                        </div>
                        <div class="dashboard-campaigns-cell">
                            <p class="dashboard-campaigns-title">
                                16 social postings on woman rights and movements around the world
                            </p>
                            <span class="dashboard-campaigns-text small">
                                NEXT TASK: <strong>LAUNCH</strong>
                            </span>
                        </div>
                    </div>
                    <div class="dashboard-campaigns">
                        <div class="dashboard-campaigns-cell">
                            <ul class="dashboard-campaign-images-list">
                                <li>
                                    <img src="/assets/images/avatar.jpg" alt="#">
                                </li>
                                <li>
                                    <img src="/assets/images/avatar.jpg" alt="#">
                                </li>
                            </ul>
                        </div>
                        <div class="dashboard-campaigns-cell">
                            <p class="dashboard-campaigns-title">
                                16 social postings on woman rights and movements around the world
                            </p>
                            <span class="dashboard-campaigns-text small">
                                NEXT TASK: <strong>LAUNCH</strong>
                            </span>
                        </div>
                    </div>
                    <div class="dashboard-campaigns">
                        <div class="dashboard-campaigns-cell">
                            <ul class="dashboard-campaign-images-list">
                                <li>
                                    <img src="/assets/images/avatar.jpg" alt="#">
                                </li>
                                <li>
                                    <img src="/assets/images/avatar.jpg" alt="#">
                                </li>
                            </ul>
                        </div>
                        <div class="dashboard-campaigns-cell">
                            <p class="dashboard-campaigns-title">
                                16 social postings on woman rights and movements around the world
                            </p>
                            <span class="dashboard-campaigns-text small">
                                NEXT TASK: <strong>LAUNCH</strong>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel max-height">
                    <div class="panel-header">
                        <h4 class="panel-sidebar-title-secondary">Activity Feed</h4>
                    </div>
                    <div class="panel-container">
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
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel max-height">
                    <div class="panel-header">
                        <h4 class="panel-sidebar-title-secondary">
                            Team Members
                            <a href="#">
                                INVITE
                                <i class="icon-edit-user"></i>
                            </a>
                        </h4>
                    </div>
                    <div class="dashboard-members-container">
                        <div class="dashboard-ideas-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-members-cell">
                            <p class="dashboard-ideas-text">Jason Simmons</p>
                            <span class="dashboard-members-text small">jasonsimm@google.com</span>
                        </div>
                        <div class="dashboard-members-cell">
                            <span class="dashboard-ideas-text small">
                                <i class="icon-checklist"></i>
                                35
                            </span>
                        </div>
                    </div>
                    <div class="dashboard-members-container">
                        <div class="dashboard-ideas-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-members-cell">
                            <p class="dashboard-ideas-text">Jason Simmons</p>
                            <span class="dashboard-members-text small">jasonsimm@google.com</span>
                        </div>
                        <div class="dashboard-members-cell">
                            <span class="dashboard-ideas-text small">
                                <i class="icon-checklist"></i>
                                35
                            </span>
                        </div>
                    </div>
                    <div class="dashboard-members-container">
                        <div class="dashboard-ideas-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-members-cell">
                            <p class="dashboard-ideas-text">Jason Simmons</p>
                            <span class="dashboard-members-text small">jasonsimm@google.com</span>
                        </div>
                        <div class="dashboard-members-cell">
                            <span class="dashboard-ideas-text small">
                                <i class="icon-checklist"></i>
                                35
                            </span>
                        </div>
                    </div>
                    <div class="dashboard-members-container">
                        <div class="dashboard-members-cell">
                            <img src="/assets/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-members-cell">
                            <p class="dashboard-ideas-text">Jason Simmons</p>
                            <span class="dashboard-members-text small">jasonsimm@google.com</span>
                        </div>
                        <div class="dashboard-members-cell">
                            <span class="dashboard-ideas-text small">
                                <i class="icon-checklist"></i>
                                35
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
-->
@stop