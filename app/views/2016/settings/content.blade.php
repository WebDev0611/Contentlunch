@extends('2016.layout.master')


@section('content')

<div class="workspace">
    <div class="panel clearfix">
        <aside class="panel-sidebar right-separator">
            <div class="panel-container text-center">
                <div class="settings-profile-image">
                    <img src="/images/avatar.jpg" alt="#">
                </div>
                <div class="settings-profile-info">
                    <h4>Storm Trooper</h4>
                    <span>New York, USA</span>
                </div>

                <span class="settings-profile-subscription">Paid Subscription</span>

                <label for="#">Paid Monthly</label>
                <h3 class="settings-profile-heading">$700</h3>

                <label for="#">Max Users</label>
                <h3 class="settings-profile-heading">$700</h3>

                <div class="form-group">
                    <a href="#" class="text-blue text-uppercase">
                        Upgrade Subscription
                    </a>
                </div>
                <div class="form-group">
                    <label for="#">Payment Info</label>
                    <span>
                        VISA X-1203
                        <a href="#" class="text-blue text-uppercase">
                            <i class="icon-edit"></i>
                            Edit
                        </a>
                    </span>
                </div>
                <div class="form-group">
                    <label for="AutoRenew" class="checkbox-primary text-inline">
                        <input id="AutoRenew" type="checkbox">
                        <span>Auto Renew</span>
                    </label>
                </div>
            </div>
        </aside>
        <div class="panel-main left-separator">
            <div class="panel-header">
                <ul class="panel-tabs text-center">
                   <li >
                        <a href="/2016/settings">Account Settings</a>
                    </li>
                    <li>
                        <a href="/2016/settings/content">Content Connections</a>
                    </li>
                    <li class="active">
                        <a href="/2016/settings/content">Content Settings</a>
                    </li>
                    <li>
                        <a href="/2016/settings/seo">SEO Settings</a>
                    </li>
                </ul>
            </div>
            <ul class="settings-nav">
                <li class="active">
                    <a href="/2016/settings/content">General Content</a>
                </li>
                <li>
                    <a href="/2016/settings/buying">Personas / Buying Stage</a>
                </li>
            </ul>
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