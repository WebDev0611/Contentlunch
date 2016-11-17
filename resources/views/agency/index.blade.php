@extends('layouts.master')

@section('content')
<div class="workspace">
    <div class="container-fluid">

        <h3 class="page-head">Clients Overview</h3>

        <!-- Dashboard Content -->
        <div class="content">

            <div class="row tight">

                <!-- Main Column -->
                <div class="col-md-9">
                    <div class="panel">

                        <!-- Content Block -->
                        <div class="panel-content">

                            <div class="panel-header">
                                <div class="panel-sidebar-title">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <p>{{ $accounts->count() }} Clients</p>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button type="button" class="button button-small withstarticon"><i class="icon-add"></i>NEW CLIENT</button>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="dashboard-content-box height-double">
                                <table class="table table-list">
                                    <thead>
                                        <tr>
                                            <th>Client</th>
                                            <th># of Projects</th>
                                            <th>Invited Guests</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @include('agency.partials.agency_rows')
                                    </tbody>
                                </table>
                            </div>

                            </div> <!-- End Content Block -->





                        </div>
                        </div> <!-- End Main Column -->


                        <!-- Side Column -->
                        <div class="col-md-3">

                            <!-- Panel: Invited Guests -->
                            <div class="panel">
                                <div class="panel-header">
                                    <h4 class="panel-sidebar-title-secondary">
                                    Invited Guests
                                    </h4>
                                </div>
                                <div class="panel-container nopadding height-double">
                                    <div class="dashboard-ideas-container">
                                        <div class="dashboard-ideas-cell">
                                            <img src="/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                                        </div>
                                        <div class="dashboard-ideas-cell">
                                            <p class="dashboard-ideas-text">Content mix: post 16 social postings</p>
                                            <span class="dashboard-ideas-text small">3 Days Ago</span>
                                        </div>
                                    </div>
                                    <div class="dashboard-ideas-container">
                                        <div class="dashboard-ideas-cell">
                                            <img src="/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                                        </div>
                                        <div class="dashboard-ideas-cell">
                                            <p class="dashboard-ideas-text">Content mix: post 16 social postings</p>
                                            <span class="dashboard-ideas-text small">3 Days Ago</span>
                                        </div>
                                    </div>
                                    <div class="dashboard-ideas-container">
                                        <div class="dashboard-ideas-cell">
                                            <img src="/images/avatar.jpg" alt="#" class="dashboard-tasks-img">
                                        </div>
                                        <div class="dashboard-ideas-cell">
                                            <p class="dashboard-ideas-text">Content mix: post 16 social</p>
                                            <span class="dashboard-ideas-text small">3 Days Ago</span>
                                        </div>
                                        <div class="dashboard-ideas-cell">
                                            <div class="dashboard-ideas-dropdown">
                                                <button type="button" class="button button-action" data-toggle="dropdown">
                                                <i class="icon-add-circle"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li>
                                                        <a href="#">Write It</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div> <!-- End Panel: Recent Ideas -->

                                </div>  <!-- End Side Column -->
                            </div>


                            </div> <!-- End Dashboard Content -->


                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="modal-invite-client" tabindex="-1" role="dialog" aria-labelledby="Invite Client">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Invite Client</h4>
                                </div>
                                <div class="modal-body">

                                    <div class="inner">
                                        <p class="intro">Invite client to ContentLaunch to share content, plans and more.</p>

                                        <div class="input-form-group">
                                            <label for="#">Invite</label>
                                            <input type="text" class="input" placeholder="One or more e-mail addresses">
                                        </div>

                                        <div class="input-form-group tight">
                                            <label for="#">Allow access to</label>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="list-checks">
                                                        <label for="access1" class="checkbox-primary">
                                                            <input id="access1" type="checkbox">
                                                            <span>Ideas</span>
                                                        </label>
                                                        <label for="access2" class="checkbox-primary">
                                                            <input id="access2" type="checkbox">
                                                            <span>Content</span>
                                                        </label>
                                                        <label for="access3" class="checkbox-primary">
                                                            <input id="access3" type="checkbox">
                                                            <span>Calendar</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="list-checks">
                                                        <label for="access1" class="checkbox-primary">
                                                            <input id="access1" type="checkbox">
                                                            <span>Ideas</span>
                                                        </label>
                                                        <label for="access2" class="checkbox-primary">
                                                            <input id="access2" type="checkbox">
                                                            <span>Content</span>
                                                        </label>
                                                        <label for="access3" class="checkbox-primary">
                                                            <input id="access3" type="checkbox">
                                                            <span>Calendar</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <button class="button button-extend text-uppercase">
                                        Send Invitation
                                        </button>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @stop