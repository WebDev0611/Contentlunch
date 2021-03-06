@extends('layouts.master')


@section('content')

<div class="workspace">
    <div class="panel clearfix">
        @include('settings.partials.sidebar')
        <div class="panel-main left-separator">
            <div class="panel-header">
                @include('settings.partials.navigation')
            </div>

            @include('elements.freemium-alert')

            <ul class="settings-nav">
                <li class="active"><a href="#content" data-toggle='tab'>Content Strategy</a></li>
                <li><a href="#personas" data-toggle='tab'>Personas</a></li>
                <li><a href="#buying-stages" data-toggle='tab'>Buying Stages</a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id='content'>
                    <div class="panel-container">
                        @include('settings.partials.contentSettings.general_content')
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id='personas'>
                    <div class="panel-container">
                        @include('settings.partials.contentSettings.personas')
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane" id='buying-stages'>
                    <div class="panel-container">
                        @include('settings.partials.contentSettings.buying_stages')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
<script src="{{ elixir('js/content-settings.js', null) }}"></script>

<script type="text/javascript">
    $(function(){
        //tasks
        $('#add-task-button').click(function() {
            add_task(addTaskCallback);
        });

        function addTaskCallback(task) {
            $('#addTaskModal').modal('hide');
        }
    });
</script>
@stop

