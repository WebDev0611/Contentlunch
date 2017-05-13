@extends('layouts.minimal')

@section('content')
<div class="landing">
    <a href="#" class="landing-logo">
        <img src="/images/logo-full.svg" alt="#">
    </a>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="onboarding-container">
                    @if($errors->count()>0)
                        <div class="alert alert-danger">
                            <p>{{ $errors->first('name') }}</p>
                            <p>{{ $errors->first('email') }}</p>
                            <p>{{ $errors->first('password') }}</p>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h3 class="onboarding-heading text-center">Welcome to ContentLaunch</h3>
                            <h5 class="onboarding-text text-center">
                                You have been invited as a guest to the {{ $accountName }} account.
                                Please complete the form below to get started.
                            </h5>
                        </div>
                    </div>

                    {{
                        Form::open([
                            'route' => [ 'guests.store', $guestInvite ],
                            'name' => 'signup_form',
                            'files' => 'true',
                            'id' => 'profile_settings',
                        ])
                    }}
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="row">

                                <div class="col-md-8">
                                    @include('guests.partials.form')
                                </div>
                                <div class="col-md-4">
                                    @include('onboarding.partials.avatar')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            {{ Form::submit('Next Step', ['class' => 'button button-extend text-uppercase']) }}
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script src="js/avatar_view.js"></script>
<script src="js/onboarding.js"></script>
@stop
