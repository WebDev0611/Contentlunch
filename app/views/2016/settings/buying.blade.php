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
                        <a href="/2016/settings/connections">Content Connections</a>
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
                <li>
                    <a href="/2016/settings/content">General Content</a>
                </li>
                <li class="active">
                    <a href="javascript:;">Personas / Buying Stage</a>
                </li>
            </ul>
            <div class="panel-container">
                <div class="row">
                    <div class="col-md-8">
                        <p class="settings-text">
                            These Personas and Buying Stages will be used in content and can be changed as needed.
                        </p>
                    </div>
                    <div class="col-md-4">
                        <button class="button button-small">
                            <i class="icon-add"></i>
                            New Stage
                        </button>
                        <button class="button button-small">
                            <i class="icon-add"></i>
                            New Persona
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="settings-table">
                            <thead>
                                <tr>
                                    <th>NAME</th>
                                    <th>SUSPECTS</th>
                                    <th>PROSPECTS</th>
                                    <th>LEADS</th>
                                    <th>OPPORTUNITIES</th>
                                    <th>CREATE STAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>CMO</td>
                                    <td>
                                        Description of how a CMO acts  at the Suspect Stage.
                                        Notice how there is no “more” link in any of the descriptions on this row.
                                    </td>
                                    <td>
                                        Description of how a CMO acts  at the Suspect Stage.
                                        This is the expanded des- cription of the Prospect CMO.
                                    </td>
                                    <td>
                                        Description of how a CMO acts  at the Suspect Stage.
                                    </td>
                                    <td>
                                        Description of how a CMO acts  at the Suspect Stage.
                                        This may be more than the 3 rows and is shown here in expanded state.
                                    </td>
                                    <td>
                                        <a href="#">
                                            <i class="icon-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-8 col-md-offset-2">
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