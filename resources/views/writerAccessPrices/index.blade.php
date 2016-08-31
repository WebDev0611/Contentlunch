@extends('layouts.master')
@section('content')

    <div class="workspace">
        <div class="container-fluid">

            <h3 class="page-head">Writer Access Prices</h3>

            <!-- Dashboard Content -->
            <div class="content">

                <div class="row tight">

                    <!-- Main Column -->
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel">

                            <!-- Content Block -->
                            <div class="panel-content">

                                <div class="panel-header">
                                    <div class="panel-sidebar-title">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <p>Price Management</p>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <button type="button" class="button button-small withstarticon"><i class="icon-add"></i>New Price</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                <div class="dashboard-content-box">
                                    <table class='table table-striped table-bordered table-hover'>
                                        <tr>
                                            <th>Asset type</th>
                                            <th>Writer level</th>
                                            <th>Wordcount</th>
                                            <th>Fee</th>
                                            <th style="width: 102px;">&nbsp;</th>
                                        </tr>
                                        @foreach($writerAccessPrices as $writerAccessPrice)
                                            <tr>
                                                <td>{{ @$writerAccessPrice->name }}</td>
                                                <td>{{ @$writerAccessPrice->writer_level }} Stars</td>
                                                <td>{{ @$writerAccessPrice->wordcount }}</td>
                                                <td>${{ number_format(@$writerAccessPrice->fee, 2) }}</td>
                                                <td>
                                                    <a href='/writerAccessPrices/{{$writerAccessPrice->id}}/edit' class='btn btn-default'>
                                                        <i class="icon-edit"></i>
                                                    </a>

                                                    {{ Form::open(['url'=>'/writerAccessPrices/'.$writerAccessPrice->id, 'method'=>'delete', 'style'=>'display:inline;']) }}
                                                    <button type="submit" class="btn btn-default" onclick="return confirm('Are you sure you want to delete this entry?')">
                                                        <i class="icon-trash"></i>
                                                    </button>
                                                    {{ Form::close() }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>

                            </div> <!-- End Content Block -->

                        </div>
                    </div> <!-- End Main Column -->

                </div>

            </div> <!-- End Dashboard Content -->


        </div>
    </div>


@stop
