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
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id='content'>
                                <div class="panel-container">
                                    content
                                </div>
                            </div>

                            <div role="tabpanel" class="tab-pane" id='users'>
                                <div class="panel-container">
                                    users
                                </div>
                            </div>

                            <div role="tabpanel" class="tab-pane" id='tasks'>
                                <div class="panel-container">
                                    tasks
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