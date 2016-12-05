@extends('layouts.master')

@section('content')

<div class="workspace">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9" id="tab-container">
                <div class="panel">
                    <div class="panel-header">
                        <ul class="panel-tabs spacing">
                            <li class="active">
                                <a href="#content" data-toggle='tab'>Content</a>
                            </li>
                            <li><a href="#users" data-toggle='tab'>Users</a></li>
                            <li><a href="#tasks" data-toggle='tab'>Tasks</a></li>
                            <li><a href="#ideas" data-toggle='tab'>Ideas</a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id='content'>
                                <div class="panel-container">
                                    @include('search.partials.content_search_tab')
                                </div>
                            </div>

                            <div role="tabpanel" class="tab-pane" id='users'>
                                <div class="panel-container">
                                    @include('search.partials.user_search_tab')
                                </div>
                            </div>

                            <div role="tabpanel" class="tab-pane" id='tasks'>
                                <div class="panel-container">
                                    @include('search.partials.task_search_tab')
                                </div>
                            </div>

                            <div role="tabpanel" class="tab-pane" id='ideas'>
                                <div class="panel-container">
                                    ideas
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection