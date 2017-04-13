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
                @php
                    $isPublished = isset($content) && $content->status  && $content->status->slug == 'published';
                @endphp
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
                                        @if (!$isCollaborator || $isPublished)
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
                                            @if (!$isCollaborator || $isPublished)
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
                                            @if (!$isCollaborator || $isPublished)
                                            disabled="disabled"
                                            @endif
                                            value="ready_to_publish">
                                            SUBMIT
                                        </button>

                                        @if ($isCollaborator)
                                        <button type="button" class="button button-small dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                @if ($isPublished)
                                                disabled="disabled"
                                                @endif>
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            {{-- <li><a href="#">Preview</a></li> --}}
                                            {{-- <li><a href="#">Export to PDF</a></li> --}}
                                            {{-- <li><a href="#">Park</a></li> --}}
                                            <li><a href="{{ route('archived_contents.update', $content) }}">Archive</a></li>
                                            <li><a href="{{ route('contentDelete', $content->id) }}">Delete</a></li>
                                            <li><a id="export-word" href="{{route('export.content', [$content->id, 'docx'])}}" download>Export to Word document</a></li>
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
                        <li @if ($content->content_status_id >= 3) class='active' @endif><i class="icon-connect"></i></li>
                        <li @if ($content->content_status_id >= 2) class='active' @endif><i class="icon-alert"></i></li>
                        <li @if ($content->content_status_id >= 1) class='active' @endif><i class="icon-edit"></i></li>
                        <li @if ($content->content_status_id >= 0) class='active' @endif><i class="icon-idea"></i></li>
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

                                        if (!$isCollaborator || $isPublished) {
                                            $contentTypeOptions['disabled'] = 'disabled';
                                        }

                                    @endphp
                                    {!!
                                        Form::select(
                                            'content_type_id',
                                            $contentTypeDropdown,
                                            old('content_type_id'),
                                            $contentTypeOptions
                                        )
                                    !!}
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="input-form-group">
                                    <label for="dueDate">DUE DATE</label>
                                    @php
                                        $dueDateOptions = [
                                            'class' => 'input-calendar datetimepicker input form-control',
                                            'id' => 'dueDate'
                                        ];

                                        if (!$isCollaborator || $isPublished) {
                                            $dueDateOptions['disabled'] = 'disabled';
                                        }
                                    @endphp
                                    {!! Form::text('due_date', old('due_date'), $dueDateOptions) !!}
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

                                if (!$isCollaborator || $isPublished) {
                                    $titleOptions['disabled'] = 'disabled';
                                }
                            @endphp
                            {!!
                                Form::text('title', old('title') ?: $content->title, $titleOptions)
                            !!}
                        </div>

                        <div class="input-form-group flexible-fields flexible-fields-email">
                            <label>SUBJECT</label>
                            @php
                                $emailSubjectOptions = [
                                    'placeholder' => 'Enter email subject',
                                    'class' => 'input input-larger form-control',
                                    'id' => 'email_subject'
                                ];

                                if (!$isCollaborator || $isPublished) {
                                    $titleOptions['disabled'] = 'disabled';
                                }
                            @endphp
                            {!! Form::text('email_subject', old('email_subject'), $emailSubjectOptions) !!}
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

                                        if (!$isCollaborator || $isPublished) {
                                            $connectionsOptions['disabled'] = 'disabled';
                                        }
                                    @endphp
                                    {!!
                                        Form::select(
                                            'connection_id',
                                            $connections,
                                            old('connection_id'),
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
                                    'id' => 'title',
                                    'rows' => '20'
                                ];
                            @endphp
                            @if ($isCollaborator && !$isPublished)
                                {!!
                                    Form::textarea('body', old('body'), $contentOptions)
                                !!}
                            @else
                                {!! @isset($content) ? $content->body : '' !!}
                            @endif
                        </div>

                        <div class="input-form-group">
                            @php
                                $tagsOptions = [
                                    'id' => 'tag-editor',
                                ];
                            @endphp
                            <label>TAGS</label>
                            @if ($isCollaborator && !$isPublished)
                                {!! Form::text('tags', old('tags') ?: '', $tagsOptions) !!}
                            @else
                                {!! Form::text('tags', $content->present()->tags, [ 'class' => 'form-control', 'disabled' => 'disabled']) !!}
                            @endif
                        </div>

                        <div class="input-form-group">
                            <label for="related">RELATED CONTENT</label>
                            @php
                                $relatedContentOptions = [
                                    'multiple'=> 'multiple',
                                    'class' => 'input selectpicker form-control',
                                    'id' => 'related'
                                ];

                                if (!$isCollaborator || $isPublished) {
                                    $relatedContentOptions['disabled'] = 'disabled';
                                }
                            @endphp
                            {!!
                                Form::select(
                                    'related[]',
                                    $relatedContentDropdown,
                                    old('related') ?: @isset($content) ? $content->related->lists('id')->toArray() : '',
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
                                <li>
                                    <a href="{{ $file->filename }}">{{ $file->name }}</a>
                                    <a data-id="{{ $file->id }}"
                                       class="attachment-delete btn btn-default btn-xs"
                                       href="#">
                                        <span class="icon icon-trash"></span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="input-form-group @if (!$isCollaborator || $isPublished) hide @endif">
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

                                        if (!$isCollaborator || $isPublished) {
                                            $options['disabled'] = 'disabled';
                                        }
                                    @endphp
                                    {!!
                                        Form::select(
                                            'buying_stage_id',
                                            $buyingStageDropdown,
                                            old('buying_stage_id'),
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

                                        if (!$isCollaborator || $isPublished) {
                                            $options['disabled'] = 'disabled';
                                        }
                                    @endphp
                                    {!!
                                        Form::select(
                                            'persona_id',
                                            $personaDropdown,
                                            old('persona_id'),
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

                        <div class="form-delimiter flexible-fields-hide flexible-fields-hide-email">
                            <span>
                                <em>SEO Information</em>
                            </span>
                        </div>

                        <div class="row flexible-fields-hide flexible-fields-hide-email">
                            <div class="col-sm-4">
                                <div class="input-form-group">
                                    <label for="metaTitle">META TITLE TAG</label>
                                    @php
                                        $metaTitleTagsOptions = [
                                            'placeholder' => 'Enter page title',
                                            'class' => 'input input-larger form-control',
                                            'id' => 'metaTitle'
                                        ];

                                        if (!$isCollaborator || $isPublished) {
                                            $metaTitleTagsOptions['disabled'] = 'disabled';
                                        }
                                    @endphp
                                    {!!
                                        Form::text(
                                            'meta_title',
                                            old('meta_title'),
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

                                        if (!$isCollaborator || $isPublished) {
                                            $keywordOptions['disabled'] = 'disabled';
                                        }
                                    @endphp
                                    {!! Form::text('meta_keywords', old('meta_keywords'), $keywordOptions) !!}
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

                                        if (!$isCollaborator || $isPublished) {
                                            $metaDescriptorOptions['disabled'] = 'disabled';
                                        }
                                    @endphp
                                    {!! Form::text('meta_description', old('meta_description'), $metaDescriptorOptions) !!}
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
                                <li class="text-right">
                                    <a class="form-list-image-link" href="{{ $image->filename }}">
                                        <img src="{{ $image->filename }}" alt="">
                                    </a>
                                    <a data-id="{{ $image->id }}"
                                       class="image-delete btn btn-default btn-xs"
                                       href="#"><span class="icon icon-trash"></span></a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="input-form-group @if (!$isCollaborator || $isPublished) hide @endif">
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
                            <img src="/images/cl-avatar2.png" alt="#" class="create-image">
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

@section('scripts')
<script>
    var TWEET_CONTENT_TYPE = {!! App\ContentType::whereName('Tweet')->first()->id !!};
</script>
<script type='text/javascript'>
    (function() {

        var source = {!! $tagsJson !!} || [];
        var contentTags = {!! $contentTagsJson !!};

        $('#tag-editor').tagEditor({
            placeholder: 'Select tags...',
            initialTags: contentTags,
            autocomplete: {
                source: source,
                minLength: 1,
            },
        });
    })();
</script>
<script src="/js/content.js"></script>
@stop
