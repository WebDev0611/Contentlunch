@extends('layouts.master')

@section('content')
<div class="workspace">
    <h4 class="text-center">Get Content Written</h4>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="onboarding-container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="create-step">
                                <span class="create-step-point active"></span>
                                <span class="create-step-point"></span>
                                <span class="create-step-point"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="input-form-group">
                                <label for="#">CONTENT TITLE</label>
                                <input type="text" class="input" placeholder="Enter content title (visible to writer)">
                            </div>
                            <div class="input-form-group">
                                <label for="#">INSTRUCTIONS</label>
                                <textarea name="#" id="#" class="input" placeholder="Enter instructions writer should follow (i.e. tone of the article, target group,specific things to mention / omit etc.)"></textarea>
                            </div>
                            <div class="input-form-group">
                                <label for="#">NARRATIVE VOICE</label>
                                <div class="select">
                                    <select name="" id="">
                                        <option value="#">First Person Plural</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <a href="/get_written/2" class="button button-extend text-uppercase">Next Step</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop