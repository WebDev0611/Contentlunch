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
               <!--<li>
                    <a href="/plan/prescription">Content Prescription</a>
                </li> -->
                <li>
                    <a href="/plan/ideas">Ideas</a>
                </li>
            </ul>
        </div>
        <div class="panel-container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="form-group">
                        <div class="input-form-button">
                            <input type="text" id="trend-search-input" placeholder="Search..." class="input-search-icon">
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

@include('plan.createmodal')


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
                    <%= author %>
                </p>
            </div>
            <div class="tombstone-social">
                <div class="tombstone-cell">
                    <i class="icon-share"></i>
                    <%= total_shares %>
                </div>
                <div class="tombstone-cell">
                    <i class="icon-facebook-mini"></i>
                    <%= fb_shares %>
                </div>
                <div class="tombstone-cell">
                    <i class="icon-twitter2"></i>
                    <%= tw_shares %>
                </div>
                <div class="tombstone-cell">
                    <i class="icon-google-plus"></i>
                    <%= google_shares %>
                </div>
                <div class="tombstone-cell">
                    <i class="icon-youtube"></i>
                    <%= video %>
                </div>
            </div>
        </div>
    </div>
</script>
@stop

@section('scripts')
<script src="/js/trends.js"></script>
@stop