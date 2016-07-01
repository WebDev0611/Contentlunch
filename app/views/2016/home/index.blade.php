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
            <div class="col-md-1">
                <div class="dashboard-donut-chart donut-chart primary pull-right">
                    <div class="slice one"></div>
                    <div class="slice two"></div>
                    <div class="chart-center">
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="dashboard-content-score">
                    <span>CURRENT<br>CONTENT SCORE</span>
                </div>
            </div>
            <div class="col-md-3 text-right">
                <div class="dashboard-layout-container">
                    <span>Writer / Editor Layout</span>
                    <ul class="dashboard-layout">
                        <li class="active">
                            <a href="javascript:;">
                                <i class="icon-chart"></i>
                            </a>
                        </li>
                        <li>
                            <a href="/2016/home/tasks">
                                <i class="icon-itemlist"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">
                <div class="panel">
                    <div class="dashboard-stats">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="#" class="dashboard-stats-label">Content Score History</label>
                                <div class="dashboard-stats-label-chart-wrapper"></div>
                            </div>
                            <div class="col-md-4">
                                <label for="#" class="dashboard-stats-label">Content Items</label>
                                <div class="dashboard-stats-label-chart-wrapper"></div>
                            </div>
                            <div class="col-md-4">
                                <label for="#" class="dashboard-stats-label">Stage Breakdown</label>
                                <div class="dashboard-stats-label-chart-wrapper"></div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-header">
                        <ul class="panel-tabs spacing">
                            <li class="active">
                                <a href="/#/">Top Performing Content</a>
                            </li>
                            <li>
                                <a href="/#/">Active Campaigns</a>
                            </li>
                        </ul>
                    </div>
                    <div class="dashboard-performing-container">
                        <div class="dashboard-performing-cell">
                            <img src="/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-performing-cell">
                            <h5 class="dashboard-performing-title">
                                Content mix: post 3 blogs, 16 social postings, 1 book per month
                            </h5>
                        </div>
                        <div class="dashboard-performing-cell">
                            <span class="dashboard-performing-text small">
                                LAUNCHED: <strong>05/05/2016</strong>
                            </span>
                        </div>
                        <div class="dashboard-performing-cell">
                            <i class="icon-arrange-mini"></i>
                        </div>
                        <div class="dashboard-performing-cell">
                            <h5 class="dashboard-performing-strong">11K</h5>
                        </div>
                    </div>
                    <div class="dashboard-performing-container">
                        <div class="dashboard-performing-cell">
                            <img src="/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-performing-cell">
                            <h5 class="dashboard-performing-title">
                                Content mix: post 3 blogs, 16 social postings, 1 book per month
                            </h5>
                        </div>
                        <div class="dashboard-performing-cell">
                            <span class="dashboard-performing-text small">
                                LAUNCHED: <strong>05/05/2016</strong>
                            </span>
                        </div>
                        <div class="dashboard-performing-cell">
                            <i class="icon-arrange-mini"></i>
                        </div>
                        <div class="dashboard-performing-cell">
                            <h5 class="dashboard-performing-strong">11K</h5>
                        </div>
                    </div>
                    <div class="dashboard-performing-container">
                        <div class="dashboard-performing-cell">
                            <img src="/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-performing-cell">
                            <h5 class="dashboard-performing-title">
                                Content mix: post 3 blogs, 16 social postings, 1 book per month
                            </h5>
                        </div>
                        <div class="dashboard-performing-cell">
                            <span class="dashboard-performing-text small">
                                LAUNCHED: <strong>05/05/2016</strong>
                            </span>
                        </div>
                        <div class="dashboard-performing-cell">
                            <i class="icon-arrange-mini"></i>
                        </div>
                        <div class="dashboard-performing-cell">
                            <h5 class="dashboard-performing-strong">11K</h5>
                        </div>
                    </div>
                    <div class="dashboard-performing-container">
                        <div class="dashboard-performing-cell">
                            <img src="/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-performing-cell">
                            <h5 class="dashboard-performing-title">
                                Content mix: post 3 blogs, 16 social postings, 1 book per month
                            </h5>
                        </div>
                        <div class="dashboard-performing-cell">
                            <span class="dashboard-performing-text small">
                                LAUNCHED: <strong>05/05/2016</strong>
                            </span>
                        </div>
                        <div class="dashboard-performing-cell">
                            <i class="icon-arrange-mini"></i>
                        </div>
                        <div class="dashboard-performing-cell">
                            <h5 class="dashboard-performing-strong">11K</h5>
                        </div>
                    </div>
                    <div class="dashboard-performing-container">
                        <div class="dashboard-performing-cell">
                            <img src="/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-performing-cell">
                            <h5 class="dashboard-performing-title">
                                Content mix: post 3 blogs, 16 social postings, 1 book per month
                            </h5>
                        </div>
                        <div class="dashboard-performing-cell">
                            <span class="dashboard-performing-text small">
                                LAUNCHED: <strong>05/05/2016</strong>
                            </span>
                        </div>
                        <div class="dashboard-performing-cell">
                            <i class="icon-arrange-mini"></i>
                        </div>
                        <div class="dashboard-performing-cell">
                            <h5 class="dashboard-performing-strong">11K</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel max-height">
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
                            <img src="/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-ideas-cell">
                            <p class="dashboard-ideas-text">Content mix: post 16 social postings</p>
                            <span class="dashboard-ideas-text small">3 Days Ago</span>
                        </div>
                    </div>
                    <div class="dashboard-ideas-container">
                        <div class="dashboard-ideas-cell">
                            <img src="/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                        </div>
                        <div class="dashboard-ideas-cell">
                            <p class="dashboard-ideas-text">Content mix: post 16 social postings</p>
                            <span class="dashboard-ideas-text small">3 Days Ago</span>
                        </div>
                    </div>
                    <div class="dashboard-ideas-container">
                        <div class="dashboard-ideas-cell">
                            <img src="/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
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
                        <h4 class="panel-sidebar-title-secondary">Activity Feed</h4>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop