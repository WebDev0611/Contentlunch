@extends('layouts.master')

@section('content')
    <div class="workspace">

        <!-- Pannel Container -->
        <div class="panel clearfix">

            <!-- Main Pane -->
            <div class="panel-main">

                  {{ Form::open(array('url' => 'edit')) }}
                <!-- Panel Header -->
                <div class="panel-header">
                    <div class="panel-options">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Content editor</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="head-actions">
                                    <button type="submit"
                                            class="button button-outline-secondary button-small delimited">SAVE
                                    </button>

                                    <button type="button" class="button button-small disabled">PUBLISH</button>

                                    <div class="btn-group">
                                        <button type="button" class="button button-small">SUBMIT</button>
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
        <div  class="alert alert-danger" id="formError">
            <p><strong>Oops! We had some errors:</strong>
                <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </p>
        </div>
    @endif
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="input-form-group">
                                    <label for="content_type">CONTENT TYPE</label>
                                   {!! Form::select('content_type', $contenttypedd, null , array('class' => 'input selectpicker form-control', 'id' => 'contentType')) !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-form-group input-drop">
                                    <label for="author">AUTHOR</label>
                                   {!! Form::select('author', $authordd, null , array('class' => 'input form-control', 'id' => 'author')) !!}
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
                                        {!! Form::text('due_date', null, array('class' => ' input form-control', 'id' => 'dueDate')) !!}
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
                            {!! Form::text('title', null, array('placeholder' => 'Enter content title', 'class' => 'input input-larger form-control', 'id' => 'title')) !!}
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="input-form-group">
                                    <label for="connections">CONTENT DESTINATION</label>
                                   {!! Form::select('connections', $connectionsdd, null , array('class' => 'input form-control', 'id' => 'connections')) !!}
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
                        <div class="editor" style="background-color: rgba(0,0,0,.1); min-height: 530px; margin-bottom: 25px;">
                            {!! Form::textarea('content', null, array('placeholder' => 'Enter content', 'class' => 'input input-larger form-control', 'id' => 'title')) !!}
                        </div>


                        <div class="input-form-group">
                            <label for="tags">TAGS</label>
                            {!! Form::select('tags[]', $tagsdd, null , array('multiple'=>'multiple', 'class' => 'input selectpicker form-control', 'id' => 'tags')) !!}
                        </div>

                        <div class="input-form-group">
                            <label for="related">RELATED CONTENT</label>
                            {!! Form::select('related[]', $relateddd, null , array('multiple'=>'multiple', 'class' => 'input selectpicker form-control', 'id' => 'related')) !!}
                        </div>

                        <div class="input-form-group">
                            <label for="#">ATTACHMENTS</label>

                            <div class="fileupload">
                                <i class="icon-link picto"></i>
                                <p class="msgtitle">Click to attach one or more files</p>
                                <input type="file" class="input input-upload">
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
                                    {!! Form::select('campaign', $campaigndd, null , array('class' => 'input form-control', 'id' => 'campaign')) !!}
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
                                    {!! Form::text('meta_title', null, array('placeholder' => 'Enter page title', 'class' => 'input input-larger form-control', 'id' => 'metaTitle')) !!}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-form-group input-drop">
                                    <label for="metaKeywords">KEYWORDS</label>
                                    {!! Form::text('meta_keywords', null, array('placeholder' => 'Separate by commas', 'class' => 'input input-larger form-control', 'id' => 'metaKeywords')) !!}
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-form-group">
                                    <label for="metaDescriptor">META DESCRIPTOR</label>
                                    {!! Form::text('meta_descriptor', null, array('placeholder' => 'Enter page description', 'class' => 'input input-larger form-control', 'id' => 'metaDescriptor')) !!}
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
                                <input type="file" class="input input-upload">
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
            <aside class="panel-sidebar hide">
              @include('content.partials.editor.sidebar')
            </aside> <!-- End Side Pane -->

        </div>  <!-- End Panel Container -->
    </div>
@stop


@section('scripts')
<script type="text/javascript">
    $(function() {

        $('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD'
        });

       $('.selectpicker').selectpicker({
            style : 'btn-white',
            liveSearch: true,
            size: 15
        });
    });
</script>
@stop
