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
                <li>
                    <a href="/plan/ideas">Ideas</a>
                </li>
            </ul>
        </div>

        @include('elements.freemium-alert-2', ['restriction' => 'search for 10 content trends'])

        <div class="panel-container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="form-group">
                        <div class="input-form-button">
                            <input type="text" id="trend-search-input" placeholder="Enter a topic, industry or keyword" class="input-search-icon">
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
                    <!--
                        <button type="button" class="button button-outline-primary button-small text-uppercase">
                            CURATE IT
                        </button> -->
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
@include('plan.sharetrendmodal')
@stop

@section('scripts')
@include('plan.partials.backbone_templates')
<script src="/js/trends.js"></script>
@stop