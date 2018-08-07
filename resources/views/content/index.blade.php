@extends('layouts.master')

@section('content')
<div class="workspace">
    <div class="panel clearfix">
        <div class="panel-main">
            @include('content.partials.dashboard.panel_tabs')

            <content-dashboard></content-dashboard>
        </div>
        {{--
        <aside class="panel-sidebar">
            @include('content.partials.dashboard.ideas-sidebar')
        </aside>
        --}}
    </div>
</div>
@stop

@section('scripts')
<script>
    $('#add-task-button').click(function() {
        add_task(addTaskCallback);
    });

    function addTaskCallback(task) {
        $('#addTaskModal').modal('hide');
    }
</script>
@stop