@extends('layouts.master')


@section('content')
<div class="workspace">
    <div class="panel clearfix">
        <div class="panel-main">
            <div class="panel-header">
                <div class="panel-options">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Idea editor</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="button button-outline-secondary button-small">SAVE</button>
                            <div class="btn-group">
                                <button type="button" class="button button-small">WRITE IT</button>
                                <button type="button" class="button button-small dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="#">Reject Idea</a></li>
                                    <li><a href="#">Park Idea</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="input-form-group">
                            <label for="#">CONCEPT NAME</label>
                            <input type="text" class="input" placeholder="Enter your concept name" value="{{$name}}">
                        </div>
                        <div class="input-form-group">
                            <label for="#">EXPLAIN YOUR IDEA</label>
                            <textarea rows="4" class="input" placeholder="Explain idea in a paragraph or so">{{$text}}</textarea>
                        </div>
                        <div class="input-form-group">
                            <label for="#">TAGS</label>
                            <input type="text" class="input" placeholder="Enter comma separated tags" value="{{$tags}}">
                        </div>
                        <div class="form-group">
                            <fieldset class="form-fieldset clearfix">
                                <legend class="form-legend">Collaborators</legend>
                                <ul class="images-list pull-left">
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
                                <div class="dropdown pull-right">
                                    <button type="button" class="button button-action large" data-toggle="dropdown">
                                        <i class="icon-add-circle"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li class="dropdown-header-secondary">
                                            <span class="dropdown-header-secondary-text">
                                                Select team member
                                            </span>
                                            <button class="button button-micro pull-right text-uppercase">
                                                Submit
                                            </button>
                                        </li>
                                        <li>
                                            <input type="text" class="dropdown-header-secondary-search" placeholder="Team Member Name">
                                        </li>
                                        <li>
                                            <label for="Friend" class="checkbox-image">
                                                <input id="Friend" type="checkbox">
                                                <span>
                                                    <img src="/images/avatar.jpg" alt="#">
                                                </span>
                                            </label>
                                            <label for="Friend" class="checkbox-image">
                                                <input id="Friend" type="checkbox">
                                                <span>
                                                    <img src="/images/avatar.jpg" alt="#">
                                                </span>
                                            </label>
                                            <label for="Friend" class="checkbox-image">
                                                <input id="Friend" type="checkbox">
                                                <span>
                                                    <img src="/images/avatar.jpg" alt="#">
                                                </span>
                                            </label>
                                            <label for="Friend" class="checkbox-image">
                                                <input id="Friend" type="checkbox">
                                                <span>
                                                    <img src="/images/avatar.jpg" alt="#">
                                                </span>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </fieldset>
                        </div>
                        <fieldset class="form-fieldset">
                            <legend class="form-legend">Invite Guests</legend>
                            <div class="form-group">
                                <input type="text" class="input input-secondary" placeholder="Enter one of more addresses">
                            </div>
                            <div class="form-group">
                                <button class="button button-tertiary button-extend text-uppercase">Submit</button>
                            </div>
                            <label for="#">Allow Access To</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="B2B" class="radio-secondary">
                                        <input id="B2B" type="radio">
                                        <span>Initial idea</span>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label for="B2B" class="radio-secondary">
                                        <input id="B2B" type="radio">
                                        <span>Calendar</span>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label for="B2B" class="radio-secondary">
                                        <input id="B2B" type="radio">
                                        <span>Content Draft</span>
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                        <div class="form-delimiter">
                            <span>
                                <em>Selected Content</em>
                            </span>
                        </div>
                        @foreach($contents as $content)
                        <div class="tombstone tombstone-horizontal tombstone-active clearfix">
                            <div class="tombstone-image">
                                <img src="{{$content->image}}" alt="">
                            </div>
                            <div class="tombstone-container">
                                <h3>{{$content->title}}</h3>
                                <p>
                                    {{$content->link}}
                                </p>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
        <aside class="panel-sidebar">
            <div class="panel-header">
                <h4 class="panel-sidebar-title">Ideas activity feed</h4>
            </div>
            <div class="panel-container plan-activity-dark">
                <div class="plan-activity-box-container">
                    <div class="plan-activity-box-img">
                        <img src="/images/avatar.jpg" alt="#">
                    </div>
                    <div class="plan-activity-box plan-activity-box-light">
                        <span class="plan-activity-title">
                            <a href="#">Jane</a> commented on
                            <a href="#"> Write blog post</a> on
                            <a href="#">online banking</a>
                        </span>
                        <p class="activity-text">
                            Suspendisse tincidunt eu lectus nec Suspen disse tincidunt eu lectus nec  vestibulum.
                            Etiam eget dolor...
                        </p>
                    </div>
                </div>
                <div class="plan-activity-box-container">
                    <div class="plan-activity-box-img">
                        <img src="/images/avatar.jpg" alt="#">
                    </div>
                    <div class="plan-activity-box plan-activity-box-light">
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
                    <div class="plan-activity-box plan-activity-box-light">
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
                    <div class="plan-activity-box plan-activity-box-light">
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
    </div>
</div>

@stop

@section('scripts')
<script src="/js/idea_editor.js"></script>
@stop