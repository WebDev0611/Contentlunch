@extends('layouts.master')

@section('content')
<div class="workspace">
    <h4 class="text-center">Create Content</h4>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="create-tabs-container">
                <ul class="create-tabs">
                    <li class="active">
                        <a href="#WriteContent" data-toggle="tab">
                            <i class="icon-edit-content"></i>
                            <h3>Write Content</h3>
                            <p>
                                Create your blogs, ebooks, landing pages & more!
                            </p>
                        </a>
                    </li>
                    <li>
                        <a href="#GetContentWritten" data-toggle="tab">
                            <i class="icon-edit-user"></i>
                            <h3>Get Content Written</h3>
                            <p>
                                Have our team of writers produce your content!
                            </p>
                        </a>
                    </li>
                    <!--
                    <li>
                        <a href="#ImportContent" data-toggle="tab">
                            <i class="icon-export"></i>
                            <h3>Import Content</h3>
                            <p>
                                Suspendisse tincidunt eu lectus nec vestibulum. Etiam eget dolor...
                            </p>
                        </a>
                    </li>
                    <li>
                        <a href="#UploadContent" data-toggle="tab">
                            <i class="icon-entry"></i>
                            <h3>Upload Content</h3>
                            <p>
                                Suspendisse tincidunt eu lectus nec vestibulum. Etiam eget dolor...
                            </p>
                        </a>
                    </li>
                    -->
                </ul>
                <div class="create-tabs-wrapper tab-content">
                    <div id="WriteContent" class="create-tabs-content tab-pane active">
                        @include('content.partials.create.write_content')
                    </div>
                    <div id="GetContentWritten" class="create-tabs-content tab-pane">
                        @include('content.partials.create.get_content_written')
                    </div>
                    <div id="ImportContent" class="create-tabs-content tab-pane">
                        @include('content.partials.create.import_content')
                    </div>
                    <div id="UploadContent" class="create-tabs-content tab-pane">
                        @include('content.partials.create.upload_content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="basecamp" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">AUTHORIZATION NEEDED</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-2">
                            <img src="/images/basecamp.png" alt="#" class="img-responsive center-block">
                        </div>
                        <div class="col-md-4 text-center">
                            <p>
                                To be able to import content from Basecamp you need to authorize ContentLaunch
                                to be able to access the Basecamp content. Curabitur maximus et augue eget accumsan.
                                Mauris quis augue vel lorem aliquet convallis sit amet ac dui.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <button class="button text-uppercase button-extend" data-dismiss="#basecamp" data-toggle="modal" data-target="#import">
                            Authorize
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="import" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Select content to import from Basecamp</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <button class="button text-uppercase button-extend">IMPORT SELECTED</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop