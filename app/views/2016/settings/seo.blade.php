@extends('2016.layout.master')

@section('content')

<div class="workspace">
    <div class="panel clearfix">
        <aside class="panel-sidebar right-separator">
            <div class="panel-container text-center">
                <div class="settings-profile-image">
                    <img src="/assets/images/avatar.jpg" alt="#">
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
                    <li>
                        <a href="/2016/settings/content">Content Settings</a>
                    </li>
                    <li class="active">
                        <a href="/2016/settings/seo">SEO Settings</a>
                    </li>
                </ul>
            </div>
            <div class="panel-container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <p>
                            If your site uses a SEO plugin, please select it from the list.
                            This will let Content Launch send HTML, titles and meta descriptions to your CMS.
                        </p>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="input-form-group">
                                    <label for="#">SELECT PLUGIN</label>
                                    <div class="select">
                                        <select name="" id="">
                                            <option value="#">Inbound Writer Integration</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="button button-large button-extend">Submit</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="input-form-group">
                                    <label for="#">SELECT PLUGIN</label>
                                    <div class="select">
                                        <select name="" id="">
                                            <option value="#">Yoast</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="button button-large button-extend">Submit</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="input-form-group">
                                    <label for="#">SELECT PLUGIN</label>
                                    <div class="select">
                                        <select name="" id="">
                                            <option value="#">Metatag (Drupal)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="button button-large button-extend">Submit</button>
                            </div>
                        </div>

                        <div class="input-form-group">
                            <button class="button button-extend">Save Changes</button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <i class="icon-notification"></i>
                        <p>
                            Content Launch supports the following plugins:
                            <strong>
                                Inbound Writer Integration, Yoast,
                                All in One SEO, Genesis Theme SEO, SEO Ultimate, Sales Power, Sales Machine, NSM Better
                                Meta, LG Better Meta, Metatag (Drupal)
                            </strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop