@extends('layouts.master')

@section('content')
    <div class="workspace">
        <div class="panel clearfix">
            <div class="panel-main">
                @include('content.partials.dashboard.panel_tabs')
            </div>
        </div>
    </div>
@endsection

