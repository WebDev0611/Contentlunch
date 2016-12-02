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
                                <a href="#">Content</a>
                            </li>
                            <li><a href="#">Users</a></li>
                            <li><a href="#">Tasks</a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        Panel body.
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection