@extends('layouts.master')

@section('content')
    <div class="workspace">

        <!-- Pannel Container -->
        <div class="panel clearfix">

            <!-- Main Pane -->
            <div class="panel-main">
@if (isset($content))
{!! Form::model($content, array('url' => url('edit') . '/' . $content->id)) !!}
@else
{{ Form::open(array('url' => 'edit', 'files'=>'true')) }}
@endif
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
                                        value="written_content">
                                        SAVE
                                    </button>

                                    @if (isset($content))
                                        <a href="{{ route('contentPublish', $content->id) }}" class="button button-small">PUBLISH</a>
                                    @endif
                                    <div class="btn-group">
                                        <button
                                            type="submit"
                                            class="button button-small"
                                            name="action"
                                            value="ready_to_publish">
                                            SUBMIT
                                        </button>
                                        <button type="button" class="button button-small dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a href="#">Preview</a></li>
                                            <li><a href="#">Export to PDF</a></li>
                                            <li><a href="#">Park</a></li>
                                            <li><a href="#">Delete</a></li>
                                        </ul>
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
                            <div class="alert alert-danger" id="formError">
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
                            <div class="col-sm-4">
                                <div class="input-form-group">
                                    <label for="content_type">CONTENT TYPE</label>
                                    {!! Form::select('content_type', $contenttypedd, @isset($content)? $content->content_type_id : ''  , array('class' => 'input selectpicker form-control', 'id' => 'contentType', 'data-live-search' => 'true', 'title' => 'Choose Content Type')) !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-form-group input-drop">
                                    <label for="author">AUTHOR</label>
                                   {!! Form::select('author[]', $authordd, @isset($content) ?  $content->authors->lists('id')->toArray() : '' , array('multiple' =>'multiple', 'class' => 'input selectpicker form-control', 'id' => 'author',  'data-live-search' => 'true', 'title' => 'Choose All Authors' )) !!}
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
                            <div class="col-sm-4">
                                <div class="input-form-group">
                                    <label for="dueDate">DUE DATE</label>
                                    <div class='input-group date datetimepicker'>
                                        {!! Form::text('due_date', @isset($content)? $content->due_date : '', array('class' => ' input form-control', 'id' => 'dueDate')) !!}
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
                            {!! Form::text('title', @isset($content)? $content->title : '', array('placeholder' => 'Enter content title', 'class' => 'input input-larger form-control', 'id' => 'title')) !!}
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="input-form-group">
                                    <label for="connections">CONTENT DESTINATION</label>
                                    {!! Form::select('connections', $connectionsdd, @isset($content)? $content->connection_id : '' , array('class' => 'input form-control', 'id' => 'connections')) !!}
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
                                <button class="button button-outline-secondary button-extend withstarticon"><i
                                            class="icon-person-aura"></i>INVITE INFLUENCERS
                                </button>
                            </div>
                        </div>

                        <!-- Editor container -->
                        <div class="character-counter">
                            <span class="count"></span> out of 140 characters
                        </div>

                        <div class="editor" style="background-color: rgba(0,0,0,.1); min-height: 530px; margin-bottom: 25px;">
                            {!! Form::textarea('content', @isset($content)? $content->body : '', array('placeholder' => 'Enter content', 'class' => 'input input-larger form-control wysiwyg', 'id' => 'title')) !!}
                        </div>


                        <div class="input-form-group">
                            <label for="tags">TAGS</label>
                            {!! Form::select('tags[]', $tagsdd, @isset($content) ? $content->tags->lists('id')->toArray() : '' , array('multiple'=>'multiple', 'class' => 'input selectpicker form-control', 'id' => 'tags', 'data-live-search' => 'true', 'title' => 'Select Tags')) !!}
                        </div>

                        <div class="input-form-group">
                            <label for="related">RELATED CONTENT</label>
                            {!! Form::select('related[]', $relateddd,  @isset($content)? $content->related->lists('id')->toArray() : ''  , array('multiple'=>'multiple', 'class' => 'input selectpicker form-control', 'id' => 'related')) !!}
                        </div>

                        <div class="input-form-group">
                            <label for="#">ATTACHMENTS</label>

                            <div class="fileupload">
                                <i class="icon-link picto"></i>
                                <p class="msgtitle">Click to attach one or more files</p>
                                <input type="file" class="input input-upload" multiple="" name="files[]">
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
                                    {!! Form::select('buying_stage', $stageddd, null , array('class' => 'input form-control', 'id' => 'buyingStage')) !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-form-group input-drop hide">
                                    <label for="#">PERSONA</label>
                                    <select name="" class="input">
                                        <option selected disabled>Select Persona</option>
                                        <option>CMO</option>
                                        <option>Persona 2</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-form-group">
                                    <label for="campaign">CAMPAIGN</label>
                                    {!! Form::select('campaign', $campaigndd, @isset($content)? $content->campaign_id : '' , array('class' => 'input form-control', 'id' => 'campaign')) !!}
                                </div>
                            </div>
                        </div>


                        <!-- SEO Information -->

                        <div class="form-delimiter">
                            <span>
                                <em>SEO Information</em>
                            </span>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-form-group">
                                    <label for="metaTitle">META TITLE TAG</label>
                                    {!! Form::text('meta_title', @isset($content)? $content->meta_title : '', array('placeholder' => 'Enter page title', 'class' => 'input input-larger form-control', 'id' => 'metaTitle')) !!}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-form-group input-drop">
                                    <label for="metaKeywords">KEYWORDS</label>
                                    {!! Form::text('meta_keywords', @isset($content)? $content->meta_keywords : '', array('placeholder' => 'Separate by commas', 'class' => 'input input-larger form-control', 'id' => 'metaKeywords')) !!}
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-form-group">
                                    <label for="metaDescriptor">META DESCRIPTOR</label>
                                    {!! Form::text('meta_descriptor', @isset($content) ? $content->meta_description : '', array('placeholder' => 'Enter page description', 'class' => 'input input-larger form-control', 'id' => 'metaDescriptor')) !!}
                                </div>
                            </div>
                            <div class="col-sm-6 hide">
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
                        </div>


                        <!-- Image Attachment -->

                        <div class="form-delimiter">
                            <span>
                                <em>Image</em>
                            </span>
                        </div>


                        <div class="input-form-group">
                            <div class="fileupload">
                                <i class="icon-content picto"></i>
                                <p class="msgtitle">Click to upload one or more images</p>
                                <input type="file" class="input input-upload" multiple="multiple" name="images[]">
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
            <aside class="panel-sidebar">
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


@section('scripts')
<script type="text/javascript">
    $(function() {

        var CharacterCounterView = Backbone.View.extend({
            characters: 0,

            initialize: function() {
                this.$el.hide();
                this.render();
            },

            render: function() {
                this.$el.find('.count').text(this.characters);

                if (this.characters > 140) {
                    this.invalidCount();
                } else {
                    this.validCount();
                }

                return this;
            },

            invalidCount: function() {
                if (!this.$el.hasClass('invalid-count')) {
                    this.$el.addClass('invalid-count');
                }
            },

            validCount: function() {
                if (this.$el.hasClass('invalid-count')) {
                    this.$el.removeClass('invalid-count');
                }
            },

            show: function() {
                this.$el.slideDown('fast');
            },

            hide: function() {
                this.$el.slideUp('fast');
            },

            update: function(content) {
                var html = content;
                var div = document.createElement("div");
                div.innerHTML = html;
                var text = div.textContent || div.innerText || "";

                this.characters = text.length;
                this.render();
            }
        });

        var characterCounter = new CharacterCounterView({ el: '.character-counter' });

        characterCounter.hide();

        tinymce.init({
            selector: 'textarea.wysiwyg',  // change this value according to your HTML
            plugin: 'a_tinymce_plugin',
            a_plugin_option: true,
            a_configuration_option: 400,
            setup: function(editor) {
                editor.on('keyup', function(e) {
                    characterCounter.show();
                    characterCounter.update(editor.getContent());
                });
            }
        });

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
    });
</script>
@stop

