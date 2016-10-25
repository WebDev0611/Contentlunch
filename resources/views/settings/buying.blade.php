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
            <ul class="settings-nav">
                <li>
                    <a href="/settings/content">General Content</a>
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