@extends('layouts.master')

@section('content')
<div class="workspace">
    <div class="panel clearfix">
        @include('settings.partials.profile_sidebar')
        <div class="panel-main left-separator">
            <div class="panel-header">
                @include('settings.partials.navigation')
            </div>

            <div class="dashboard-content-box collaborators-list-container height-double">

            </div>

        </div>
    </div>
</div>
@stop

@section('scripts')
<script src="/js/account-settings.js"></script>
@stop