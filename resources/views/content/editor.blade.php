@extends('layouts.master')

@section('content')
    <div class="workspace">

        <!-- Pannel Container -->
        <div class="panel clearfix">

            <!-- Main Pane -->
            <div class="panel-main">
                @if (isset($content))
                    {!! Form::model($content, ['url' => url('edit') . '/' . $content->id, 'files' => 'true' ]) !!}
                @else
                    {{ Form::open(['url' => 'edit', 'files'=>'true']) }}
                @endif
                {{ Form::hidden('content_id', $content->id) }}
                <!-- Panel Header -->
                <div class="panel-header">
                    <div class="panel-options">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Content editor</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="head-actions">
                                    <button
                                        type="submit"
                                        class="button button-outline-secondary button-small delimited"
                                        name="action"
                                        @if (!$isCollaborator)
                                        disabled="disabled"
                                        @endif
                                        value="written_content">
                                        SAVE
                                    </button>

                                    @if (isset($content))
                                        <button
                                            type='submit'
                                            class="button button-small"
                                            name="action"
                                            @if (!$isCollaborator)
                                            disabled="disabled"
                                            @endif
                                            value="publish">
                                            PUBLISH
                                        </button>
                                    @endif

                                    <div class="btn-group">
                                        <button
                                            type="submit"
                                            class="button button-small"
                                            name="action"
                                            @if (!$isCollaborator)
                                            disabled="disabled"
                                            @endif
                                            value="ready_to_publish">
                                            SUBMIT
                                        </button>
                                        @if ($isCollaborator)
                                        <button type="button" class="button button-small dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            {{-- <li><a href="#">Preview</a></li> --}}
                                            {{-- <li><a href="#">Export to PDF</a></li> --}}
                                            {{-- <li><a href="#">Park</a></li> --}}
                                            <li><a href="{{ route('contentDelete', $content->id) }}">Delete</a></li>
                                        </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- End Panel Header -->

                <!-- Panel Container -->
                <div class="panel-container padded relative">
                    <!-- Stages widget -->
                    <ul class="list-unstyled list-stages list-stages-side">
                        <li><i class="icon-connect"></i></li>
                        <li><i class="icon-alert"></i></li>
                        <li class="active"><i class="icon-edit"></i></li>
                        <li class="active"><i class="icon-idea"></i></li>
                    </ul>

                    <div class="inner">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-forms" id="formError">
                                <p><strong>Oops! We had some errors:</strong>
                                    <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                    </ul>
                                </p>
                            </div>
                        @endif

                        <div class="alert alert-danger" id="twitterError" style='display:none'>
                            <p><strong>Oops! We had some errors:</strong>
                                <ul>
                                    <li>
                                        You cannot post to Twitter with more than
                                        <strong>140</strong> characters.
                                    </li>
                                </ul>
                            </p>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-form-group">
                                    <label for="content_type">CONTENT TYPE</label>
                                    @php
                                        $contentTypeOptions = [
                                            'class' => 'input selectpicker form-control',
                                            'id' => 'contentType',
                                            'data-live-search' => 'true',
                                            'title' => 'Choose Content Type',
                                        ];

                                        if (!$isCollaborator) {
                                            $contentTypeOptions['disabled'] = 'disabled';
                                        }

                                    @endphp
                                    {!!
                                        Form::select(
                                            'content_type',
                                            $contentTypeDropdown,
                                            @isset($content) ? $content->content_type_id : '',
                                            $contentTypeOptions
                                        )
                                    !!}
                                </div>
                            </div>
                            {{--
                            <div class="col-sm-4">
                                <div class="input-form-group input-drop">
                                    <label for="author">AUTHOR</label>
                                    {!!
                                        Form::select(
                                            'author[]',
                                            $authorDropdown,
                                            @isset($content) ? $content->authors->lists('id')->toArray() : '',
                                            [
                                                'multiple' =>'multiple',
                                                'class' => 'input selectpicker form-control',
                                                'id' => 'author',
                                                'data-live-search' => 'true',
                                                'title' => 'Choose All Authors'
                                            ]
                                        )
                                    !!}
                                    <div class="hide">
                                        <input type="text" class="input" placeholder="Select author" data-toggle="dropdown">
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li class="dropdown-header-secondary">
                                                <span class="dropdown-header-secondary-text">
                                                    Select team member
                                                </span>
                                                <button class="button button-micro pull-right text-uppercase">
                                                    Submit
                                                </button>
                                            </li>
                                            <li>
                                                <input type="text" class="dropdown-header-secondary-search" placeholder="Team Member Name">
                                            </li>
                                            <li>
                                                <label for="Friend" class="checkbox-image">
                                                    <input id="Friend" type="checkbox">
                                                    <span>
                                                        <img src="/images/avatar.jpg" alt="#">
                                                    </span>
                                                </label>
                                                <label for="Friend" class="checkbox-image">
                                                    <input id="Friend" type="checkbox">
                                                    <span>
                                                        <img src="/images/avatar.jpg" alt="#">
                                                    </span>
                                                </label>
                                                <label for="Friend" class="checkbox-image">
                                                    <input id="Friend" type="checkbox">
                                                    <span>
                                                        <img src="/images/avatar.jpg" alt="#">
                                                    </span>
                                                </label>
                                                <label for="Friend" class="checkbox-image">
                                                    <input id="Friend" type="checkbox">
                                                    <span>
                                                        <img src="/images/avatar.jpg" alt="#">
                                                    </span>
                                                </label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            --}}
                            <div class="col-sm-6">
                                <div class="input-form-group">
                                    <label for="dueDate">DUE DATE</label>
                                    <div class='input-group date datetimepicker'>
                                        @php
                                            $dueDateOptions = [
                                                'class' => ' input form-control',
                                                'id' => 'dueDate'
                                            ];

                                            if (!$isCollaborator) {
                                                $dueDateOptions['disabled'] = 'disabled';
                                            }
                                        @endphp
                                        {!!
                                            Form::text('due_date', @isset($content)? $content->due_date : '', $dueDateOptions)
                                        !!}
                                        <span class="input-group-addon">
                                            <i class="icon-calendar picto"></i>
                                        </span>
                                    </div>

                                   <!--  <div class="form-suffix">
                                        <i class="icon-calendar picto"></i>
                                        <input type="text" class="input datetimepicker" placeholder="Select date">
                                    </div> -->
                                </div>
                            </div>
                        </div>

                        <div class="input-form-group">
                            <label for="title">TITLE</label>
                            @php
                                $titleOptions = [
                                    'placeholder' => 'Enter content title',
                                    'class' => 'input input-larger form-control',
                                    'id' => 'title'
                                ];

                                if (!$isCollaborator) {
                                    $titleOptions['disabled'] = 'disabled';
                                }
                            @endphp
                            {!!
                                Form::text('title', @isset($content) ? $content->title : '', $titleOptions)
                            !!}
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="input-form-group">
                                    <label for="connections">CONTENT DESTINATION</label>
                                    @php
                                        $connectionsOptions = [
                                            'class' => 'input form-control',
                                            'id' => 'connections'
                                        ];

                                        if (!$isCollaborator) {
                                            $connectionsOptions['disabled'] = 'disabled';
                                        }
                                    @endphp
                                    {!!
                                        Form::select(
                                            'connections',
                                            $connections,
                                            @isset($content) ? $content->connection_id : '',
                                            $connectionsOptions
                                        )
                                    !!}
                                </div>
                            </div>
                            <div class="col-sm-4 hide">
                                <div class="input-form-group hide">
                                    <label for="#">CONTENT TEMPLATE</label>
                                    <select name="" class="input">
                                        <option selected disabled>Select template</option>
                                        <option>Template 1</option>
                                        <option>Template 2</option>
                                        <option>Template 3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4 hide">
                                <label>&nbsp;</label>
                                <button
                                    class="button button-outline-secondary button-extend withstarticon">
                                    <i class="icon-person-aura"></i>INVITE INFLUENCERS
                                </button>
                            </div>
                        </div>

                        <!-- Editor container -->
                        <div class="character-counter">
                            <span class="count"></span> out of 140 characters
                        </div>

                        <div class="editor" style="min-height: 530px; margin-bottom: 25px;">
                            <label>CONTENT BODY</label>
                            @php
                                $contentOptions = [
                                    'placeholder' => 'Enter content',
                                    'class' => 'input input-larger form-control wysiwyg',
                                    'id' => 'title'
                                ];
                            @endphp
                            @if ($isCollaborator)
                                {!!
                                    Form::textarea('content', @isset($content)? $content->body : '', $contentOptions)
                                !!}
                            @else
                                {!! @isset($content) ? $content->body : '' !!}
                            @endif
                        </div>


                        <div class="input-form-group">
                            <label for="tags">TAGS</label>
                            @php
                                $tagsOptions = [
                                    'multiple' => 'multiple',
                                    'class' => 'input selectpicker form-control',
                                    'id' => 'tags',
                                    'data-live-search' => 'true',
                                    'title' => 'Select Tags'
                                ];

                                if (!$isCollaborator) {
                                    $tagsOptions['disabled'] = 'disabled';
                                }

                            @endphp
                            {!!
                                Form::select(
                                    'tags[]',
                                    $tagsDropdown,
                                    @isset($content) ? $content->tags->lists('id')->toArray() : '',
                                    $tagsOptions
                                )
                            !!}
                        </div>

                        <div class="input-form-group">
                            <label for="related">RELATED CONTENT</label>
                            @php
                                $relatedContentOptions = [
                                    'multiple'=> 'multiple',
                                    'class' => 'input selectpicker form-control',
                                    'id' => 'related'
                                ];

                                if (!$isCollaborator) {
                                    $relatedContentOptions['disabled'] = 'disabled';
                                }
                            @endphp
                            {!!
                                Form::select(
                                    'related[]',
                                    $relatedContentDropdown,
                                    @isset($content)? $content->related->lists('id')->toArray() : '',
                                    $relatedContentOptions
                                )
                            !!}
                        </div>

                        <div class="form-delimiter">
                            <span>
                                <em>Attachments</em>
                            </span>
                        </div>

                        @if (isset($content))
                        <div class="input-form-group">
                            <ul>
                                @foreach ($files as $file)
                                <li><a href="{{ $file->filename }}">{{ $file->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="input-form-group @if (!$isCollaborator) hide @endif">
                            <div class="dropzone" id='attachment-uploader'>
                            </div>
                        </div>

                        <!-- Compaign Stage -->
                        <div class="form-delimiter">
                            <span>
                                <em>Campaign Stage</em>
                            </span>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="input-form-group">
                                    <label for="buyingStage">BUYING STAGE</label>
                                    @php
                                        $options = [
                                            'class' => 'input form-control',
                                            'id' => 'buyingStage'
                                        ];

                                        if (!$isCollaborator) {
                                            $options['disabled'] = 'disabled';
                                        }
                                    @endphp
                                    {!!
                                        Form::select(
                                            'buying_stage',
                                            $buyingStageDropdown,
                                            @isset($content) ? $content->buying_stage_id : '',
                                            $options
                                        )
                                    !!}
                                </div>
                            </div>

                            <div class="col-sm-offset-4 col-sm-4">
                                <div class="input-form-group input-drop">
                                    <label for="#">PERSONA</label>
                                    @php
                                        $options = [
                                            'class' => 'input form-control',
                                            'id' => 'persona'
                                        ];

                                        if (!$isCollaborator) {
                                            $options['disabled'] = 'disabled';
                                        }
                                    @endphp
                                    {!!
                                        Form::select(
                                            'persona',
                                            $personaDropdown,
                                            @isset($content) ? $content->persona_id : '',
                                            $options
                                        )
                                    !!}
                                </div>
                            </div>

                            {{--
                            <div class="col-sm-4">
                                <div class="input-form-group">
                                    <label for="campaign">CAMPAIGN</label>
                                    {!!
                                        Form::select(
                                            'campaign',
                                            $campaignDropdown,
                                            @isset($content) ? $content->campaign_id : '' ,
                                            [
                                                'class' => 'input form-control',
                                                'id' => 'campaign'
                                            ]
                                        )
                                    !!}
                                </div>
                            </div>
                            --}}
                        </div>

                        <!-- SEO Information -->

                        <div class="form-delimiter">
                            <span>
                                <em>SEO Information</em>
                            </span>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="input-form-group">
                                    <label for="metaTitle">META TITLE TAG</label>
                                    @php
                                        $metaTitleTagsOptions = [
                                            'placeholder' => 'Enter page title',
                                            'class' => 'input input-larger form-control',
                                            'id' => 'metaTitle'
                                        ];

                                        if (!$isCollaborator) {
                                            $metaTitleTagsOptions['disabled'] = 'disabled';
                                        }
                                    @endphp
                                    {!!
                                        Form::text(
                                            'meta_title',
                                            @isset($content) ? $content->meta_title : '',
                                            $metaTitleTagsOptions
                                        )
                                    !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-form-group input-drop">
                                    <label for="metaKeywords">KEYWORDS</label>
                                    @php
                                        $keywordOptions = [
                                            'placeholder' => 'Separate by commas',
                                            'class' => 'input input-larger form-control',
                                            'id' => 'metaKeywords'
                                        ];

                                        if (!$isCollaborator) {
                                            $keywordOptions['disabled'] = 'disabled';
                                        }
                                    @endphp
                                    {!!
                                        Form::text(
                                            'meta_keywords',
                                            @isset($content) ? $content->meta_keywords : '',
                                            $keywordOptions
                                        )
                                    !!}
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="input-form-group">
                                    <label for="metaDescriptor">META DESCRIPTION TAG</label>
                                    @php
                                        $metaDescriptorOptions = [
                                            'placeholder' => 'Enter page description',
                                            'class' => 'input input-larger form-control',
                                            'id' => 'metaDescriptor'
                                        ];

                                        if (!$isCollaborator) {
                                            $metaDescriptorOptions['disabled'] = 'disabled';
                                        }
                                    @endphp
                                    {!!
                                        Form::text(
                                            'meta_descriptor',
                                            @isset($content) ? $content->meta_description : '',
                                            $metaDescriptorOptions
                                        )
                                    !!}
                                </div>
                            </div>
                            <!--
                            <div class="col-sm-6">
                                <div class="input-form-group input-drop">
                                    <label>&nbsp;</label>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <button class="button button-outline-secondary button-extend withstarticon">
                                                <i class="icon-seo-magnifier"></i>SEO CHECK
                                            </button>
                                            <p class="help-block">Analyze content for prelim SEO score</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <button class="button button-outline-secondary button-extend withstarticon">
                                                <i class="icon-view-magnifier"></i>SEARCH PREVIEW
                                            </button>
                                            <p class="help-block">Analyze content for prelim SEO score</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            -->
                        </div>


                        <!-- Image Attachment -->

                        <div class="form-delimiter">
                            <span>
                                <em>Images</em>
                            </span>
                        </div>

                        @if (isset($content))
                        <div class="input-form-group">
                            <ul class="form-image-list">
                                @foreach ($images as $image)
                                <li>
                                    <a href="{{ $image->filename }}">
                                        <img src="{{ $image->filename }}" alt="">
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="input-form-group @if (!$isCollaborator) hide @endif">
                            <div class="dropzone" id='image-uploader'>
                            </div>
                        </div>

                        <div class="form-delimiter hide">
                            <span>
                                <em>Custom Fields</em>
                            </span>
                        </div>
                    </div>

                </div>  <!-- End Panel Container -->

                  {{ Form::close() }}
            </div> <!-- End Main Pane -->

            <!-- Side Pane -->
            <aside class="panel-sidebar" id='editor-panel-sidebar'>
                @include('content.partials.editor.sidebar')
            </aside> <!-- End Side Pane -->

        </div>  <!-- End Panel Container -->
    </div>


<div id="launch" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">LAUNCH CONTENT</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <p class="text-gray text-center">
                            Here are the 5 content connections you can push out to, check the ones you want, and
                            click launch and they go out in real time to be published.
                            Need a “confirmation” screen as well.
                        </p>
                        <label for="dieselEngines1" class="checkbox-tag">
                            <input id="dieselEngines1" type="checkbox">
                            <span>Dwight’s Twitter Feed</span>
                        </label>
                        <label for="dieselEngines1" class="checkbox-tag">
                            <input id="dieselEngines1" type="checkbox">
                            <span>Dwight’s Twitter Feed</span>
                        </label>
                        <label for="dieselEngines1" class="checkbox-tag">
                            <input id="dieselEngines1" type="checkbox">
                            <span>Dwight’s Twitter Feed</span>
                        </label>
                        <label for="dieselEngines1" class="checkbox-tag">
                            <input id="dieselEngines1" type="checkbox">
                            <span>Dwight’s Twitter Feed</span>
                        </label>
                        <div class="form-group text-center">
                            <a href="#" class="link-gray">
                                ADD NEW
                                <i class="icon-add"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <button class="button button-primary text-uppercase button-extend"  data-toggle="modal" data-target="#launchCompleted">LAUNCH</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="launchCompleted" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">CONTENT LAUNCHED</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 text-center">
                        <i class="modal-icon-success icon-check-large"></i>
                        <div class="form-group">
                            <img src="/images/avatar.jpg" alt="#" class="create-image">
                            <h4>Blog post on online banking</h4>
                        </div>
                        <p class="text-gray">IS NOW PUBLISHED TO:</p>
                        <div class="modal-social">
                            <span>
                                <i class="icon-facebook-official"></i>
                            </span>
                            <span>
                                <i class="icon-trello"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <button class="button text-uppercase button-extend">Go To Dashboard!</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop


@section('styles')

@stop

@section('scripts')
<script type='text/javascript'>
    (function() {

        var imageUploader = new Dropzone('#image-uploader', { url: '/edit/images' });
        var attachmentUploader = new Dropzone('#attachment-uploader', { url: '/edit/attachments' });

        imageUploader.on('success', function(file, response) {
            var hiddenField = $('<input/>', {
                name: 'images[]',
                type: 'hidden',
                value: response.file
            });

            hiddenField.appendTo($('form'));
        });

        attachmentUploader.on('success', function(file, response) {
            var hiddenField = $('<input/>', {
                name: 'files[]',
                type: 'hidden',
                value: response.file
            });

            hiddenField.appendTo($('form'));
        });

    })();
</script>
<script type="text/javascript">
    $(function() {

        var contentEditor;

        var characterCounter = new CharacterCounterView({ el: '.character-counter' });

        characterCounter.hide();

        tinymce.init({
            selector: 'textarea.wysiwyg',  // change this value according to your HTML
            plugin: 'a_tinymce_plugin',
            a_plugin_option: true,
            a_configuration_option: 400,
            setup: function(editor) {
                contentEditor = editor;
                editor.on('keyup', updateCount);
            }
        });

        $('#contentType').change(updateCount);

        $('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD'
        });

        $('.selectpicker').selectpicker({
            style : 'btn-white',
            size: 10
        });

        $('.changes').hide();
        $(".showChanges").on('click', function(){
            var $this = $(this),
                divClass = $this.attr('data-class');

            $("." + divClass).toggle();
       });

        $('form').submit(function(event) {
            var TWEET_CONTENT_TYPE = "17";
            var MAX_TWEET_CHARACTERS = 140;
            var selectedContentType = $('#contentType').val();

            if (selectedContentType == TWEET_CONTENT_TYPE &&
                characterCounter.characters >= MAX_TWEET_CHARACTERS)
            {
                $('#twitterError').slideDown('fast');
                event.preventDefault();
            }
        });

        function getHeaders() {
            return {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('input[name=_token]').val()
            };
        }

        function updateCount(event) {
            if (characterCounter.isTweet()) {
                characterCounter.show();
                characterCounter.update(contentEditor.getContent());
            }
        }

        /**
         * Content task handling
         */
        var tasks = new task_collection();

        tasks.on('add', function(task) {
            var element = new ContentTaskView({ model: task });
            element.render();
            $('.content-tasks-box-container').append(element.el);
        });

        fetchTasks();

        function fetchTasks() {
            return $.ajax({
                url: '/api/contents/' + contentId() + '/tasks',
                method: 'get',
            })
            .then(function(response) {
                tasks.add(response.map(function(task) {
                    return new task_model(task);
                }));
            });
        }

        function contentId() {
            return $('input[name=content_id]').val();
        }

        $('#add-task-button').click(function() {
            add_task(addTaskCallback);
        });

        function addTaskCallback(task) {
            tasks.add(new task_model(task));
            $('#addTaskModal').modal('hide');
        }
    });
</script>
@stop

