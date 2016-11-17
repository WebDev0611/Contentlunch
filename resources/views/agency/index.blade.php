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
                    @include('agency.partials.clients_list')
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