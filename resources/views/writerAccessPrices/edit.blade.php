@extends('layouts.master')
@section('content')

    <div class="workspace">
        <div class="container-fluid">

            <h3 class="page-head">Writer Acccess Prices</h3>

            <!-- Dashboard Content -->
            <div class="content">

                <div class="row tight">

                    <!-- Main Column -->
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel">

                            <div class="panel onboarding-container">
                                <div class="r">
                                    <h4>
                                        Edit Price
                                    </h4>
                                </div>
                                <div class="">
                                    {{ Form::open(['url' => 'writerAccessPrices/'.@$writerAccessPrice->id, 'method' =>'put', 'class'=>'form', 'role'=>'form']) }}
                                    {{-- @if(@$errors)
                                         <div role='alert' class='alert alert-danger'>
                                             {{ HTML::ul($errors->all()) }}
                                         </div>
                                     @endif--}}
                                    <div class='row'>
                                        <div class='col-md-12'>
                                            <div class='form-group'>
                                                <!-- `Asset type id` Field -->
                                                {{ Form::label('asset_type_id', 'Asset type id') }}
                                                {{ Form::text('writer_access_price[asset_type_id]', @$writerAccessPrice->asset_type_id, ['class'=>'form-control']) }}
                                            </div>
                                            <div class='form-group'>
                                                <!-- `Writer level` Field -->
                                                {{ Form::label('writer_level', 'Writer level') }}
                                                {{ Form::text('writer_access_price[writer_level]', @$writerAccessPrice->writer_level, ['class'=>'form-control']) }}
                                            </div>
                                            <div class='form-group'>
                                                <!-- `Wordcount` Field -->
                                                {{ Form::label('wordcount', 'Wordcount') }}
                                                {{ Form::text('writer_access_price[wordcount]', @$writerAccessPrice->wordcount, ['class'=>'form-control']) }}
                                            </div>
                                            <div class='form-group'>
                                                <!-- `Fee` Field -->
                                                {{ Form::label('fee', 'Fee') }}
                                                {{ Form::text('writer_access_price[fee]', @$writerAccessPrice->fee, ['class'=>'form-control']) }}
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
                            </div>
                        </div>
                    </div>

                </div>

            </div>


        </div>
    </div>


@stop