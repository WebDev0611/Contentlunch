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
                                        @include ('agency.partials.agency_rows')
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- End Content Block -->
                    </div>
                </div> <!-- End Main Column -->

                <div class="col-md-3">
                    @include('agency.partials.invited_guests_sidebar')
                </div>
            </div>


        </div> <!-- End Dashboard Content -->


    </div>
</div>

<!-- Modal -->
@include('agency.partials.invite_guest_modal')
@stop