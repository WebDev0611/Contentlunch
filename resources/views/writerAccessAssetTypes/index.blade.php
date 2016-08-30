@extends('layouts.master')
@section('content')

    <div class='container'>
        <div class='row'>
            <div class='col-md-12'>
                <h3>WriterAccess Asset Types</h3>
                <table class='table table-bordered table-striped table-hover'>
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
                                <a href='writerAccessAssetTypes/{{$writerAccessAssetType->id}}/edit' class='btn btn-primary'><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                {{ Form::open(['url'=>'writerAccessAssetTypes/'.$writerAccessAssetType->id, 'method'=>'delete', 'style'=>'display:inline;']) }}
                                <button type='submit' class='btn btn-danger'>
                                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                </button>
                                {{ Form::close() }}
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

@stop
