@extends('layouts.master')


@section('content')
<div class="workspace">
    <div class="panel">
        <div class="panel-header">
            <ul class="panel-tabs text-center">
                <li >
                    <a href="/plan">Topic Generator</a>
                </li>
                <li class="active">
                    <a href="/plan/trends">Content Trends</a>
                </li>
                <li>
                    <a href="/plan/prescription">Content Prescription</a>
                </li>
            </ul>
        </div>
        <div class="panel-container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="form-group">
                        <div class="input-form-button">
                            <input type="text" placeholder="Search..." class="input-search-icon">
                            <span class="input-form-button-action">
                                <button class="button" id="trend-search">SEARCH</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-separator">
                <div class="row">
                    <div class="col-md-6">
                        <h5 id="create-alert"></h5>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="button" class="button button-outline-primary button-small text-uppercase">
                            CURATE IT
                        </button>
                        <button type="button" data-target="#createIdea" data-toggle="modal" class="button button-small text-uppercase">
                            <i class="icon-add-content icon-vertically-middle"></i>
                            Create Idea
                        </button>
                    </div>
                </div>
            </div>
            <div class="row" id="trend-results"></div>
        </div>
    </div>
</div>
<div id="createIdea" class="sidemodal large">
    <div class="sidemodal-header">
        <div class="row">
            <div class="col-md-6">
                <h4 class="sidemodal-header-title large">Create an idea from 2 selected items</h4>
            </div>
            <div class="col-md-6 text-right">
                <button type="button" class="button button-outline-primary button-small">PARK</button>
                <button class="button button-primary button-small text-uppercase">Save</button>
                <button class="sidemodal-close normal-flow" data-dismiss="modal">
                    <i class="icon-remove"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="sidemodal-container">
        <div class="input-form-group">
            <label for="#">CONCEPT NAME</label>
            <input type="text" class="input" placeholder="Enter your concept name">
        </div>
        <div class="input-form-group">
            <label for="#">EXPLAIN YOUR IDEA</label>
            <textarea rows="4" class="input" placeholder="Explain idea in a paragraph or so"></textarea>
        </div>
        <div class="input-form-group">
            <label for="#">TAGS</label>
            <input type="text" class="input" placeholder="Enter comma separated tags">
        </div>
        <div class="form-group">
            <fieldset class="form-fieldset clearfix">
                <legend class="form-legend">Collaborators</legend>
                <ul class="images-list pull-left">
                    <li>
                        <img src="/assets/images/avatar.jpg" alt="#">
                    </li>
                    <li>
                        <img src="/assets/images/avatar.jpg" alt="#">
                    </li>
                    <li>
                        <img src="/assets/images/avatar.jpg" alt="#">
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
                                <img src="/assets/images/avatar.jpg" alt="#">
                            </span>
                            </label>
                            <label for="Friend" class="checkbox-image">
                                <input id="Friend" type="checkbox">
                            <span>
                                <img src="/assets/images/avatar.jpg" alt="#">
                            </span>
                            </label>
                            <label for="Friend" class="checkbox-image">
                                <input id="Friend" type="checkbox">
                            <span>
                                <img src="/assets/images/avatar.jpg" alt="#">
                            </span>
                            </label>
                            <label for="Friend" class="checkbox-image">
                                <input id="Friend" type="checkbox">
                            <span>
                                <img src="/assets/images/avatar.jpg" alt="#">
                            </span>
                            </label>
                        </li>
                    </ul>
                </div>
            </fieldset>
        </div>
        <div class="form-group">
            <div class="select select-secondary">
                <select name="" id="">
                    <option value="#">Invite Guests</option>
                </select>
            </div>
        </div>
        <div class="form-delimiter">
            <span>
                <em>Selected Content</em>
            </span>
        </div>
        <div class="tombstone tombstone-horizontal tombstone-active clearfix">
            <div class="tombstone-image">
                <img src="http://i.imgur.com/MYB6HjU.jpg" alt="">
            </div>
            <div class="tombstone-container">
                <h3>Google self-driving car is tested on California highways</h3>
                <p>
                    Visitors to Eat Streat enjoyed an additional treat with their lunch when a range of
                    electric cars, including a top of the line Tesla, went on...
                </p>
            </div>
        </div>
        <div class="tombstone tombstone-horizontal tombstone-active clearfix">
            <div class="tombstone-image">
                <img src="http://i.imgur.com/MYB6HjU.jpg" alt="">
            </div>
            <div class="tombstone-container">
                <h3>Google self-driving car is tested on California highways</h3>
                <p>
                    Visitors to Eat Streat enjoyed an additional treat with their lunch when a range of
                    electric cars, including a top of the line Tesla, went on...
                </p>
            </div>
        </div>
    </div>
</div>

<script type="text/template" id="trend-result-template">
   <div class="col-md-3">
        <div class="tombstone">
            <div class="tombstone-image">
                <img src="<%= image %>" alt="">
                <span><%= when %>  ·  <%= source %></span>
            </div>
            <div class="tombstone-container">
                <h3><%= title %></h3>
                <p>
                    <%= body %>
                </p>
            </div>
            <div class="tombstone-social">
                <div class="tombstone-cell">
                    <i class="icon-share"></i>
                    35k
                </div>
                <div class="tombstone-cell">
                    <i class="icon-facebook-mini"></i>
                    12k
                </div>
                <div class="tombstone-cell">
                    <i class="icon-twitter2"></i>
                    4k
                </div>
                <div class="tombstone-cell">
                    <i class="icon-google-plus"></i>
                    10k
                </div>
                <div class="tombstone-cell">
                    <i class="icon-youtube"></i>
                    3k
                </div>
            </div>
        </div>
    </div>
</script>
@stop

@section('scripts')
<script src="/js/trends.js"></script>
@stop