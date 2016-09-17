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
                                Suspendisse tincidunt eu lectus nec vestibulum. Etiam eget dolor...
                            </p>
                        </a>
                    </li>
                    <li>
                        <a href="#GetContentWritten" data-toggle="tab">
                            <i class="icon-edit-user"></i>
                            <h3>Get Content Written</h3>
                            <p>
                                Suspendisse tincidunt eu lectus nec vestibulum. Etiam eget dolor...
                            </p>
                        </a>
                    </li>
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
                </ul>
                <div class="create-tabs-wrapper tab-content">
                    <div id="WriteContent" class="create-tabs-content tab-pane active">
                        <div class="row">
                        {{ Form::open(array('url' => 'edit')) }}
                            <div class="col-md-8 col-md-offset-2">
                                <div class="input-form-group">
                                    <label for="#">Content Title</label>
                                    {!! Form::text('title', '', array('placeholder' => 'Enter content title', 'class' => 'input input-larger form-control', 'id' => 'title')) !!}
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-form-group">
                                            <label for="#">CONTENT TYPE</label>
                                            <div class="select">
                                               {!! Form::select('content_type', $contenttypedd, @isset($content)? $content->content_type_id : ''  , array('class' => 'input selectpicker form-control', 'id' => 'contentType', 'data-live-search' => 'true', 'title' => 'Choose Content Type')) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-form-group">
                                            <label for="#">TEMPLATE</label>
                                            <div class="select">
                                                <select name="" id="">
                                                    <option value="#">Select Past Template</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="input-form-group">
                                    <label for="#">Campaign</label>
                                    <div class="select">
                                        <select name="" id="">
                                            <option value="#">Add to campaign</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="button button-extend text-uppercase">
                                    CREATE CONTENT
                                </button>
                            </div>
                             {{ Form::close() }}
                        </div>
                    </div>
                    <div id="GetContentWritten" class="create-tabs-content tab-pane">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="input-form-group">
                                    <label for="#">PROJECT NAME</label>
                                    <input type="text" class="input" placeholder="Enter project name">
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="#">NUMBER OF TITLES</label>
                                                <div class="range-form input-group">
                                                    <span class="input-group-addon">
                                                        <button class="button button-small button-outline-secondary">
                                                            <i class="icon-big-caret-left"></i>
                                                        </button>
                                                    </span>
                                                    <input type="text" class="input" aria-label="Amount (to the nearest dollar)">
                                                    <span class="input-group-addon">
                                                        <button class="button button-small button-outline-secondary">
                                                            <i class="icon-big-caret-right"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-form-group">
                                                    <label for="#">Project Deadline</label>
                                                    <input type="text" class="input" placeholder="Project Deadline">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-form-group">
                                            <label for="#">CONTENT TYPE</label>
                                            <div class="select">
                                                <select name="" id="">
                                                    <option value="#">Content Type</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="input-form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="#">MIN WORDS</label>
                                                    <div class="range-form input-group">
                                                    <span class="input-group-addon">
                                                        <button class="button button-small button-outline-secondary">
                                                            <i class="icon-big-caret-left"></i>
                                                        </button>
                                                    </span>
                                                        <input type="text" class="input" aria-label="Amount (to the nearest dollar)">
                                                    <span class="input-group-addon">
                                                        <button class="button button-small button-outline-secondary">
                                                            <i class="icon-big-caret-right"></i>
                                                        </button>
                                                    </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="#">MAXIMUM WORDS</label>
                                                    <div class="range-form input-group">
                                                    <span class="input-group-addon">
                                                        <button class="button button-small button-outline-secondary">
                                                            <i class="icon-big-caret-left"></i>
                                                        </button>
                                                    </span>
                                                        <input type="text" class="input" aria-label="Amount (to the nearest dollar)">
                                                    <span class="input-group-addon">
                                                        <button class="button button-small button-outline-secondary">
                                                            <i class="icon-big-caret-right"></i>
                                                        </button>
                                                    </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="#">WRITER LEVEL</label>
                                                    <div class="range-form input-group">
                                                    <span class="input-group-addon">
                                                        <button class="button button-small button-outline-secondary">
                                                            <i class="icon-big-caret-left"></i>
                                                        </button>
                                                    </span>
                                                        <input type="text" class="input" aria-label="Amount (to the nearest dollar)">
                                                    <span class="input-group-addon">
                                                        <button class="button button-small button-outline-secondary">
                                                            <i class="icon-big-caret-right"></i>
                                                        </button>
                                                    </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-form-group">
                                                        <label for="#">Base Priceline</label>
                                                        <input type="text" class="input" placeholder="Base Priceline">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="create-tabs-priceline">
                                            <span>TOTAL ORDER</span>
                                            <h4>$40.70</h4>
                                        </div>
                                        <div class="create-tabs-priceline">
                                            <span>COST / ORDER</span>
                                            <h4>$40.70</h4>
                                        </div>
                                    </div>
                                </div>
                                <button href="javascript:;" onclick="window.location.href = '/get_written';" class="button button-extend text-uppercase">
                                    SUBMIT AND START ORDERING PROCESS
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="ImportContent" class="create-tabs-content tab-pane">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <p class="text-center">Select content to import from?</p>
                                <div class="row create-social-box-container">
                                    <div class="col-md-3">
                                        <div class="create-social-box">
                                            <span>Basecamp</span>
                                            <i class="icon-basecamp"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="create-social-box">
                                            <span>Asana</span>
                                            <i class="icon-threedots"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="create-social-box active" data-toggle="modal" data-target="#basecamp">
                                            <span>Trello</span>
                                            <i class="icon-trello"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="create-social-box">
                                            <span>Dropbox</span>
                                            <i class="icon-basecamp"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="UploadContent" class="create-tabs-content tab-pane">
                        <div class="input-form-group">
                            <div class="fileupload create-file-upload">
                                <i class="icon-add-content picto"></i>
                                <p class="msgtitle">Click or drop your content to upload</p>
                                <input type="file" class="input input-upload">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <button href="/#/onboarding/2" class="button button-extend text-uppercase">
                                    UPLOAD CONTENT
                                </button>
                            </div>
                        </div>
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