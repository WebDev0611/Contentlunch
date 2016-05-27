@extends('layouts.default')
@section('content')

    <div class='container'>
        {{ Form::open(['url' => 'writerAccessPrices, 'method' =>'post', 'class'=>'form', 'role'=>'form']) }}
        @if(@$errors)
            <div role='alert' class='alert alert-danger'>
                {{ HTML::ul($errors->all()) }}
            </div>
        @endif
        <div class='row'>
            <div class='col-md-12'>
                <div class='form-group'>
                    <!-- `Asset type id` Field -->
                    {{ Form::label('asset_type_id', 'Asset type id') }}
                    {{ Form::text('writer_access_price[asset_type_id]', Input::old('writer_access_price.asset_type_id'), ['class'=>'form-control']) }}
                </div>
                <div class='form-group'>
                    <!-- `Writer level` Field -->
                    {{ Form::label('writer_level', 'Writer level') }}
                    {{ Form::text('writer_access_price[writer_level]', Input::old('writer_access_price.writer_level'), ['class'=>'form-control']) }}
                </div>
                <div class='form-group'>
                    <!-- `Wordcount` Field -->
                    {{ Form::label('wordcount', 'Wordcount') }}
                    {{ Form::text('writer_access_price[wordcount]', Input::old('writer_access_price.wordcount'), ['class'=>'form-control']) }}
                </div>
                <div class='form-group'>
                    <!-- `Fee` Field -->
                    {{ Form::label('fee', 'Fee') }}
                    {{ Form::text('writer_access_price[fee]', Input::old('writer_access_price.fee'), ['class'=>'form-control']) }}
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