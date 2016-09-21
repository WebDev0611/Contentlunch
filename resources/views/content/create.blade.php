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
                            <div class="col-md-8 col-md-offset-2">
                                <div class="input-form-group">
                                    <label for="#">Content Title</label>
                                    <input type="text" class="input" placeholder="Content Title">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-form-group">
                                            <label for="#">CONTENT TYPE</label>
                                            <div class="select">
                                                <select name="" id="">
                                                    <option value="#">Content Type</option>
                                                </select>
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
                                <button href="javascript:;" onclick="window.location.href='/edit';" class="button button-extend text-uppercase">
                                    CREATE CONTENT
                                </button>
                            </div>
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
                                                        <button class="button button-small button-outline-secondary" disabled>
                                                            <i class="icon-big-caret-left"></i>
                                                        </button>
                                                    </span>
                                                    <input type="text" class="input" name="writer_access_count" id="writer_access_count" value="1" aria-label="Number of content titles to order.">
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
                                                    <input type="text" class="input datepicker" name="dealine" id="deadline" placeholder="Project Deadline!">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-form-group">
                                            <label for="writer_access_asset_type">CONTENT TYPE</label>
                                            <div class="select">
                                                <select name="writer_access_asset_type" id="writer_access_asset_type">
                                                    @foreach($contentTypes  as $contentType)
                                                        <option value="{{$contentType->writer_access_id}}">{{$contentType->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="input-form-group">
                                            <label for="writer_access_asset_type">WORD COUNT</label>
                                            <div class="select">
                                                <select name="writer_access_word_count" id="writer_access_word_count">

                                                </select>
                                            </div>
                                        </div>
                                        <div class="input-form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="#">WRITER LEVEL</label>
                                                    <div class="select">

                                                        <select type="text" class="input" name="writer_access_writer_level" id="writer_access_writer_level" aria-label="Amount (to the nearest dollar)">
                                                            <option value="4">4 Star Writer</option>
                                                            <option value="5">5 Star Writer</option>
                                                            <option value="6">6 Star Writer</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="input-form-group">
                                                        <label for="#">Base Priceline</label>
                                                        <input type="text" class="input" disabled name="writer_access_base_price" id="writer_access_base_price"  placeholder="Base Priceline">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="create-tabs-priceline">
                                            <span>TOTAL ORDER</span>
                                            <h4 id="total_cost">$40.70</h4>
                                        </div>
                                        <div class="create-tabs-priceline">
                                            <span>COST / ORDER</span>
                                            <h4 id="price_each">$40.70</h4>
                                        </div>
                                    </div>
                                </div>
                                <button href="javascript:;" onclick="window.location.href = '/get_written';" class="button button-extend text-uppercase">
                                    SUBMIT AND START ORDERING PROCESS
                                </button>

                                <script type="text/javascript">
                                    // This is brilliant... seriously!
                                    var prices =  (function(){ return {!! $pricesJson !!}; })();
                                </script>
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