@extends('layouts.master')


@section('content')

<div class="workspace">
    <div class="panel clearfix">
        @include('settings.partials.profile_sidebar')
        <div class="panel-main left-separator">
            <div class="panel-header">
                <!-- navigation -->
                @include('settings.partials.navigation')
            </div>
            @include('settings.partials.navigation_content')
            <div class="panel-container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="input-form-group">
                            <label for="authorName" class="checkbox-primary">
                                <input id="authorName" type="checkbox">
                                <span>Include Author Name into content</span>
                            </label>
                            <label for="#">APPLY TO CONTENT TYPES</label>
                            <div class="select">
                                <select name="" id="">
                                    <option value="#">Select content types</option>
                                </select>
                            </div>
                        </div>
                        <div class="input-form-group">
                            <label for="publishingDate" class="checkbox-primary">
                                <input id="publishingDate" type="checkbox">
                                <span>Allow Publishing Date to be edited</span>
                            </label>
                            <div class="select">
                                <select name="" id="">
                                    <option value="#">Select content types</option>
                                </select>
                            </div>
                        </div>
                        <div class="input-form-group">
                            <label for="KeywordTags" class="checkbox-primary">
                                <input id="KeywordTags" type="checkbox">
                                <span>Use Keyword Tags</span>
                            </label>
                            <div class="select">
                                <select name="" id="">
                                    <option value="#">Select content types</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-delimiter">
                            <span>
                                <em>Contacts</em>
                            </span>
                        </div>
                        <div class="input-form-group">
                            <label for="#">COMPANY PUBLISHING GUIDELINES</label>
                            <textarea class="input">Explain most important elements of company’s publishing strategy. This informationwill be provided to each collaborator and guest</textarea>
                        </div>
                        <div class="input-form-group">
                            <label for="#">EXPLAIN COMPANY STRATEGY IN SHORT</label>
                            <textarea class="input">Create more content, Invite more collaborators, Schedule a Consultation, Write more blog posts</textarea>
                        </div>
                        <div class="input-form-group">
                            <button class="button button-extend">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop