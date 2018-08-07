@extends('layouts.master')
@section('content')

    <div class="workspace">
        <div class="container-fluid">

            <h3 class="page-head">Writer Access Asset Types</h3>

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
                                                <p>Asset Type Management</p>
                                            </div>
                                            {{--<div class="col-md-6 text-right">
                                                <button type="button" class="button button-small withstarticon"><i class="icon-add"></i>New Asset Type</button>
                                            </div>--}}
                                        </div>

                                    </div>
                                </div>


                                <div class="dashboard-content-box">
                                    <table class='table table-striped table-bordered table-hover'>
                                        <tr>
                                            <th>Writer access id</th>
                                            <th>Name</th>
                                            <th style="width: 103px;">&nbsp;</th>
                                        </tr>
                                            @foreach($writerAccessAssetTypes as $writerAccessAssetType)
                                                <tr>
                                                    <td>{{ @$writerAccessAssetType->writer_access_id }}</td>
                                                    <td>{{ @$writerAccessAssetType->name }}</td>
                                                    <td>
                                                        <a href='writerAccessAssetTypes/{{$writerAccessAssetType->id}}/edit' class='btn btn-primary' class='btn btn-default'>
                                                            <i class="icon-edit"></i>
                                                        </a>
                                                        {{ Form::open(['url'=>'writerAccessAssetTypes/'.$writerAccessAssetType->id, 'method'=>'delete', 'style'=>'display:inline;']) }}
                                                        <button type="submit" class="btn btn-default" onclick="return confirm('Are you sure you want to delete this entry?')">
                                                            <i class="icon-trash"></i>
                                                        </button>
                                                        {{ Form::close() }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
