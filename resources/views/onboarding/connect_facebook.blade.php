@extends('layouts.minimal')

@section('content')

<div class="landing">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="onboarding-container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <a href="#" class="landing-logo">
                                <img src="/images/logo-full.svg" alt="#">
                            </a>
                            <h5 class="onboarding-step-text text-center">
                                Configure your content connections
                            </h5>
                            <h5 class="onboarding-text text-center">
                                Connect as many services you use. Based on information from your social
                                and publishing platforms we calculate your CL score
                            </h5>
                            <div class="onboarding-step">
                                <span class="onboarding-step-point active"></span>
                                <span class="onboarding-step-point active"></span>
                                <span class="onboarding-step-point active"></span>
                                <span class="onboarding-step-point"></span>
                            </div>
                        </div>
                    </div>
                    {{ Form::open(array('url' => 'callback/facebook/account/save')) }}
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">

                            <div class="onboarding-import">
                                <p>
                                    Please use the form below to select the
                                    Facebook page you want to connect to.
                                </p>
                                {!!
                                    Form::select('facebook_account', $accountOptions, '' , [
                                        'class' => 'input selectpicker form-control',
                                        'id' => 'contentType',
                                        'data-live-search' => 'true',
                                        'title' => 'Choose Facebook Account'
                                    ])
                                !!}
                                {!! Form::hidden('connection_id', $connection_id) !!}
                                {!! Form::hidden('onboarding', true) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <button
                                type="submit"
                                class="button button-extend text-uppercase">

                                Back to the Connection Configuration step
                            </button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>

@stop
