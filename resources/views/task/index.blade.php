@extends('layouts.master')

@section('content')
    <div class="workspace">

        <!-- Pannel Container -->
        <div class="panel clearfix">

            <!-- Main Pane -->
            <div class="panel-main">

                <!-- Panel Header -->
                <div class="panel-header">
                    <div class="panel-options">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Task</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="head-actions">
                                    <button
                                        class="button button-outline-secondary button-small delimited"
                                        id="update-task">
                                        UPDATE
                                    </button>

                                    <div class="btn-group">
                                        <button
                                            class="button button-small"
                                            id="close-task">
                                            CLOSE TASK
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- End Panel Header -->

                <!-- Panel Container -->
                <div class="panel-container padded relative">


                    <div class="inner">

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="input-form-group">
                                    <label for="start_date">START DATE</label>
                                    <div class='input-group date datetimepicker'>
                                        {!! Form::text('start_date', @isset($task)? $task->start_date : '', array('class' => ' input form-control', 'id' => 'start_date')) !!}
                                        <span class="input-group-addon">
                                            <i class="icon-calendar picto"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="input-form-group">
                                    <label for="due_date">DUE DATE</label>
                                    <div class='input-group date datetimepicker'>
                                        {!! Form::text('due_date', @isset($task)? $task->due_date : '', array('class' => ' input form-control', 'id' => 'due_date')) !!}
                                        <span class="input-group-addon">
                                            <i class="icon-calendar picto"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-form-group">
                                    <label for="title">TITLE</label>
                                    {!!
                                        Form::text(
                                            'name',
                                            @isset($task) ? $task->name : '',
                                            [
                                                'placeholder' => 'Enter task name',
                                                'class' => 'input input-larger form-control',
                                                'id' => 'name'
                                            ]
                                        )
                                    !!}
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="input-form-group">
                                    <label for="url">REFERENCE URL</label>
                                    <input type="text" placeholder="http://example.com" class="form-control input-larger input " id="url" name="url" value="{{$task->url}}" /> 
                                </div>
                            </div>
                        </div>
                        <!-- Editor container -->

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="input-form-group">
                                    <label for="explanation">Explanation</label>
                                    {!!
                                        Form::textarea(
                                            'name',
                                            @isset($task) ? $task->explanation : '',
                                            [
                                                'placeholder' => 'Task explanation',
                                                'class' => 'input input-larger form-control',
                                                'id' => 'explanation'
                                            ]
                                        )
                                    !!}                               
                                </div>
                            </div>
                        </div>
                    </div>

                </div>  <!-- End Panel Container -->

            </div> <!-- End Main Pane -->

            <!-- Side Pane -->
            <aside class="panel-sidebar">
                <div id="task-status-message">
                    <div class="alert alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <div id="task-status-text"></div>
                    </div>
                </div>
                <!-- side bar here -->
            </aside> <!-- End Side Pane -->

        </div>  <!-- End Panel Container -->
    </div>

@stop


@section('styles')

@stop

@section('scripts')
<script type='text/javascript'>
(function($){
    var taskForm = {
        update_task: function(){
            var form_data = {
                name: $('#name').val(),
                start_date: $('#start_date').val(),
                due_date: $('#due_date').val(),
                explanation: $('#explanation').val(),
                _token: '{{ csrf_token() }}'
            };

            $.post('/task/update/{{$task->id}}',form_data,function(res){
                console.log(res);
                if(res.success){
                    $('#task-status-message').find('.alert').addClass('alert-success');
                    $('#task-status-text').find('.alert').text('Updated task ' + form_data.name);
                }
            });
        },
        close_task: function(){
            var form_data = {
                name: $('#name').val()
            };
            $.post('/task/close/{{$task->id}}',{
                    _token: '{{ csrf_token() }}'
                },function(res){
                if(res.success){
                    $('#task-status-message').find('.alert').addClass('alert-success');
                    $('#task-status-text').find('.alert').text('Closed task ' + form_data.name);

                }
            });
        }
    };

    $(function(){
        $('#close-task').click(function(){
            taskForm.close_task();
        });
        $('#update-task').click(function(){
            taskForm.update_task();
        });
    });
})(jQuery);
</script>

@stop

