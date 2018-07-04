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
                                        {!!
                                            Form::text(
                                                'start_date',
                                                @isset($task) ? $task->present()->startDateFormat('Y-m-d H:i') : '',
                                                [
                                                    'class' => 'input input-calendar',
                                                    'placeholder' => 'Select start date',
                                                    'id' => 'start_date'
                                                ])
                                        !!}
                                    </div>

                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="input-form-group">
                                    <label for="due_date">DUE DATE</label>
                                    <div class='input-group date datetimepicker'>
                                        {!!
                                            Form::text(
                                                'due_date',
                                                @isset($task)? $task->present()->dueDateFormat('Y-m-d H:i') : '',
                                                [
                                                    'class' => ' input input-calendar',
                                                    'placeholder' => 'Select due date',
                                                    'id' => 'due_date'
                                                ])
                                        !!}
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
                                    <input type="text"
                                        placeholder="http://example.com"
                                        class="form-control input-larger input"
                                        id="url"
                                        name="url"
                                        value="{{ @isset($task) ? $task->url : '' }}" />
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
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <input type="hidden" name="task_id" value="{{ $task->id }}" />
                        </div>

                        <div class="input-form-group">
                            <label for="#">Assign Task To</label>
                            <ul class="sidemodal-list-items" id='task-assignment-non-modal'>
                                @foreach ($assignableUsers as $user)
                                    @php
                                        $isChecked = $task->isAssignedTo($user) ? 'checked="checked"' : '';
                                    @endphp
                                    <li>
                                        <label class="checkbox-primary">
                                            <input name='assigned_users[]' type="checkbox" data-id='{{ $user->id }}' {{ $isChecked }}>
                                            <span>{{ $user->name }}</span>
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="form-delimiter">
                            <span>
                                <em>Attachments</em>
                            </span>
                        </div>

                        <div class="input-form-group">
                            <ul style="list-style-type: none;">
                                @foreach ($task->attachments as $image)
                                    <li class="">
                                     {{--   <a class="form-list-image-link attachement-link" target="_blank" href="{{ $image->filename }}">
                                            <i class="fa fa-paperclip fa-5x" style="phpmargin-top: 25%;" aria-hidden="true"></i>
                                            --}}{{--<img class="" src="{{ $image->filename }}" alt="">--}}{{--
                                        </a>--}}

                                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                                        <a target="_blank" href="{{ $image->filename }}" style="padding: 10px 5px;">
                                            {{ str_limit(basename($image->filename).PHP_EOL, 120) }}
                                        </a>

                                        <a data-id="{{ $image->id }}"
                                           class="attachment-delete btn btn-default btn-xs"
                                           href="#"><span class="icon icon-trash"></span></a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="input-form-group ">
                            <div class="dropzone" id='attachment-uploader'>
                            </div>
                        </div>

                    </div>

                </div>  <!-- End Panel Container -->

            </div> <!-- End Main Pane -->

            <!-- Side Pane -->
            <aside class="panel-sidebar">
                <div id="task-status-message">

                </div>
                <!-- side bar here -->
            </aside> <!-- End Side Pane -->

        </div>  <!-- End Panel Container -->
    </div>

@stop

@section('scripts')
<script type='text/javascript' src="{{ elixir('js/task_editor.js', null) }}"></script>
@stop
