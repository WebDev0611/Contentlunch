@extends('layouts.master')


@section('content')
<div class="workspace">
    <div class="panel">
        <div class="panel-header">
            <ul class="panel-tabs text-center">
                <li class="active">
                    <a href="javascript:;">Topic Generator</a>
                </li>
                <li>
                    <a href="/plan/trends">Content Trends</a>
                </li>
               <!-- <li>
                    <a href="/plan/prescription">Content Prescription</a>
                </li> -->
                <li>
                    <a href="/plan/ideas">Ideas</a>
                </li>
            </ul>
            <!--<a href="#" class="panel-tabs-link">
                Topic Generator Concierge
                <i class="icon-arrow-right"></i>
            </a>-->
        </div>

        @include('elements.freemium-alert', ['restriction' => ['name' => 'topics', 'limit' => 'ten']])

        <div class="panel-container" id="topic-generator">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="form-group">
                        <div class="input-form-button">
                            <input type="text" id="topic-search-val" placeholder="Search..." class="input-search-icon">
                            <span class="input-form-button-action">
                                <button class="button" id="topic-search">SEARCH</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-separator">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Select one or more topics of interest from lists below to create an idea</h5>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="button"
                            data-target="#createIdea"
                            data-toggle="modal"
                            class="button button-small text-uppercase">

                            <i class="icon-add-content icon-vertically-middle"></i>
                            Create Idea
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 panel-separator-vertical">
                    <div class="panel-separator">
                        <h5 class="panel-heading text-center">SHORT TAIL RESULTS</h5>
                        <div class="row" id="short-tail-results"></div>
                    </div>
                </div>
                <div class="col-md-6 panel-separator-vertical">
                    <div class="panel-separator">
                        <h5 class="panel-heading text-center">LONG TAIL RESULTS</h5>
                        <div class="row" id="long-tail-results"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('plan.createmodal')

@stop

@section('scripts')
@include('plan.partials.backbone_templates')
<script src="/js/topic.js"></script>
@stop