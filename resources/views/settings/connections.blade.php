@extends('layouts.master')


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
                        <a href="/settings">Account Settings</a>
                    </li>
                    <li class="active">
                        <a href="/settings/content">Content Connections</a>
                    </li>
                    <li>
                        <a href="/settings/content">Content Settings</a>
                    </li>
                    <li>
                        <a href="/settings/seo">SEO Settings</a>
                    </li>
                </ul>
            </div>
            <div class="panel-container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="settings-import">
                            <div class="settings-import-container">
                                <input type="text" class="settings-import-input" placeholder="Quick search your list of friends">
                                <div class="settings-import-action">
                                    <span>
                                        12 connections,
                                        <strong>2 active</strong>
                                    </span>
                                    <button class="button button-small" data-target="#newConnection" data-toggle="modal">
                                        <i class="icon-add"></i>
                                        NEW CONNECTION
                                    </button>
                                </div>
                                <div class="settings-import-list">
                                    <div class="settings-import-item">
                                        <div class="col-md-6">
                                            <img src="/images/avatar.jpg" alt="#" class="settings-import-item-img">
                                            <span class="settings-import-item-title">Joomla</span>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button class="button button-small">Connect</button>
                                        </div>
                                    </div>
                                    <div class="settings-import-item">
                                        <div class="col-md-6">
                                            <img src="/images/avatar.jpg" alt="#" class="settings-import-item-img">
                                            <span class="settings-import-item-title">Joomla</span>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button class="button button-small">Connect</button>
                                        </div>
                                    </div>
                                    <div class="settings-import-item">
                                        <div class="col-md-6">
                                            <img src="/images/avatar.jpg" alt="#" class="settings-import-item-img">
                                            <span class="settings-import-item-title">Joomla</span>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button class="button button-small">Connect</button>
                                        </div>
                                    </div>
                                    <div class="settings-import-item active">
                                        <div class="col-md-6">
                                            <img src="/images/avatar.jpg" alt="#" class="settings-import-item-img">
                                            <span class="settings-import-item-title">Joomla</span>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button class="button button-connected button-small">Connected</button>
                                        </div>
                                    </div>
                                    <div class="settings-import-item active">
                                        <div class="col-md-6">
                                            <img src="/images/avatar.jpg" alt="#" class="settings-import-item-img">
                                            <span class="settings-import-item-title">Joomla</span>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button class="button button-connected button-small">Connected</button>
                                        </div>
                                    </div>
                                    <div class="settings-import-item">
                                        <div class="col-md-6">
                                            <img src="/images/avatar.jpg" alt="#" class="settings-import-item-img">
                                            <span class="settings-import-item-title">Joomla</span>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button class="button button-small">Connect</button>
                                        </div>
                                    </div>
                                    <div class="settings-import-item">
                                        <div class="col-md-6">
                                            <img src="/images/avatar.jpg" alt="#" class="settings-import-item-img">
                                            <span class="settings-import-item-title">Joomla</span>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button class="button button-small">Connect</button>
                                        </div>
                                    </div>
                                    <div class="settings-import-item">
                                        <div class="col-md-6">
                                            <img src="/images/avatar.jpg" alt="#" class="settings-import-item-img">
                                            <span class="settings-import-item-title">Joomla</span>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button class="button button-small">Connect</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <i class="icon-notification"></i>
                        <p>
                            Content Connections are ut auctor nunc eu ante efficitur scelerisque.
                            Etiam ac neque molestie, placerat elit nec, blandit dui. Sed mattis fringilla rhoncus.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="newConnection" class="sidemodal large">
    <div class="sidemodal-header">
        <div class="row">
            <div class="col-md-6">
                <h4 class="sidemodal-header-title large">New Connection</h4>
            </div>
            <div class="col-md-6 text-right">
                <button type="button" class="button button-outline-primary button-small">Save</button>
                <button class="button button-primary button-small text-uppercase">Connect</button>
                <button class="sidemodal-close normal-flow" data-dismiss="modal">
                    <i class="icon-remove"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="sidemodal-container">
        <div class="row">
            <div class="col-md-9">
                <div class="input-form-group">
                    <label for="#">Connection Name</label>
                    <input type="text" class="input" placeholder="John's Blog">
                </div>
            </div>
            <div class="col-md-3">
                <label for="#" class="checkbox-ios">
                    <input type="checkbox">
                    <span>Active</span>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="input-form-group">
                    <label for="#">Connection Type</label>
                    <div class="select">
                        <select name="" id="">
                            <option value="#">Wordpress</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="input-form-group">
                    <label for="#">API Key</label>
                    <input type="text" class="input" placeholder="Pase from connection source">
                </div>
            </div>
        </div>
        <div class="input-form-group">
            <label for="#">URL</label>
            <input type="text" class="input" placeholder="Pase URL">
        </div>
    </div>
</div>
@stop