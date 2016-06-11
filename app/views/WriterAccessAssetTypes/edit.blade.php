@extends('layouts.default')
@section('content')

    <div class='container'>
        {{ Form::open(['url' => 'writerAccessAssetTypess/'.@$writerAccessAssetType->id, 'method' =>'put', 'class'=>'form', 'role'=>'form']) }}

       {{-- @if(@$errors)
            <div role='alert' class='alert alert-danger'>
                {{ HTML::ul($errors->all()) }}
            </div>
        @endif--}}

        <div class='row'>
            <div class='col-md-12'>
                <div class='form-group'>
                    <!-- `Writer access id` Field -->
                    {{ Form::label('writer_access_id', 'Writer access id') }}
                    {{ Form::text('writer_access_asset_type[writer_access_id]', @$writerAccessAssetType->writer_access_id, ['class'=>'form-control']) }}
                </div>
                <div class='form-group'>
                    <!-- `Name` Field -->
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('writer_access_asset_type[name]', @$writerAccessAssetType->name, ['class'=>'form-control']) }}
                </div>
            </div>
            <div class='col-md-12 text-right'>
                <!-- Form actions -->
                <a href='{{URL::previous()}}' class='btn btn-secondary'>Cancel</a>
                <button type='submit' class='btn btn-primary'>Submit</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3>{{ $writerAccessAssetType->name }} Prices</h3>
                <table class='table table-striped table-bordered table-hover'>
                    <tr>
                        <th>Asset type</th>
                        <th>Writer level</th>
                        <th>Wordcount</th>
                        <th>Fee</th>
                        <th style="width: 102px;">&nbsp;</th>
                    </tr>
                    @foreach($writerAccessAssetType->prices as $writerAccessPrice)
                        <tr>
                            <td>{{ @$writerAccessAssetType->name }}</td>
                            <td>{{ @$writerAccessPrice->writer_level }} Stars</td>
                            <td>{{ @$writerAccessPrice->wordcount }}</td>
                            <td>${{ number_format(@$writerAccessPrice->fee, 2) }}</td>
                            <td>
                                <a href='/writerAccessPrices/{{$writerAccessPrice->id}}/edit' class='btn btn-primary'><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>

                                {{ Form::open(['url'=>'/writerAccessPrices/'.$writerAccessPrice->id, 'method'=>'delete', 'style'=>'display:inline;']) }}
                                <button type="submit" class="btn btn-danger">
                                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                </button>
                                {{ Form::close() }}
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@stop