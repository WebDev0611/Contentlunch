@extends('admin.layouts.master')

@section('header')
    <div class="row border-bottom white-bg dashboard-header">
        <h2>Welcome, {{ Auth::user()->name }}</h2>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>User Logins</h5>
                </div>
                <div class="ibox-content">
                    <div>
                        <canvas id="lineChart" height="140"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop