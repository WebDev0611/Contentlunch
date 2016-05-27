@extends('layouts.default')
@section('content')

    <div class='container'>
        {{ Form::open(['url' => 'writerAccessAssetTypes, 'method' =>'post', 'class'=>'form', 'role'=>'form']) }}
        @if(@$errors)
            <div role='alert' class='alert alert-danger'>
                {{ HTML::ul($errors->all()) }}
            </div>
        @endif
        <div class='row'>
            <div class='col-md-12'>
                <div class='form-group'>
                    <!-- `Writer access id` Field -->
                    {{ Form::label('writer_access_id', 'Writer access id') }}
                    {{ Form::text('writer_access_asset_type[writer_access_id]', Input::old('writer_access_asset_type.writer_access_id'), ['class'=>'form-control']) }}
                </div>
                <div class='form-group'>
                    <!-- `Name` Field -->
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('writer_access_asset_type[name]', Input::old('writer_access_asset_type.name'), ['class'=>'form-control']) }}
                </div>
            </div>
            <div class='col-md-12 text-right'>
                <!-- Form actions -->
                <a href='{{URL::previous()}}' class='btn btn-default'>Cancel</a>
                <button type='submit' class='btn btn-default'>Submit</button>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@stop