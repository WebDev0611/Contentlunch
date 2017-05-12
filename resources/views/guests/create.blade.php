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
                                You have been invited as a guest to the {{ $guestInvite->account->name }} account.
                                Please complete the form below to get started.
                            </h5>
                        </div>
                    </div>

                    {{
                        Form::open([
                            'route' => 'guests.store',
                            'name' => 'signup_form',
                            'files' => 'true',
                            'id' => 'profile_settings',
                        ])
                    }}
                    <input type="hidden" name="redirect_url" value="/invite">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="row">

                                <div class="col-md-8">
                                    <div class="input-form-group">
                                        {{ Form::label('name', 'Full Name') }}
                                        {{ Form::text('name', Input::old('name'), ['placeholder' => 'your name', 'class' => 'input']) }}
                                    </div>
                                    <div class="input-form-group">
                                        {{ Form::label('email', 'Email Address') }}
                                        {{ Form::text('email', Input::old('email'), ['placeholder' => 'email', 'class' => 'input']) }}
                                    </div>
                                    <div class="input-form-group">
                                        {{ Form::label('password', 'Password') }}
                                        {{ Form::password('password', ['placeholder' => 'password','class' => 'input']) }}
                                        <div class="input-strength-indicator">
                                            <span style="width: 30%;"></span>
                                        </div>
                                        <p class="onboarding-notification-text">
                                            <i class="icon-notification"></i>
                                            Password should contain minimum 8 characters, including alphanumeric,
                                            special and both upper and lower case
                                        </p>
                                    </div>
                                    <div class="input-form-group">
                                        {{ Form::password('password_confirmation', ['placeholder' => 'repeat password','class' => 'input']) }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="onboarding-avatar" id='signup-onboarding-avatar'>
                                        <div class="onboarding-image-wrapper">
                                            <div class="loading-icon loading-icon-center"></div>
                                            @if ($avatarUrl)
                                                <img src="{{ $avatarUrl }}" alt="">
                                            @else
                                                <img src="/images/cl-avatar2.png" alt="#">
                                            @endif
                                        </div>
                                        <label for="upload" class="onboarding-avatar-button">
                                            <i class="icon-add"></i>
                                            <input id="upload" name='avatar' type="file">
                                            <span>Upload Avatar</span>
                                        </label>
                                    </div>
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
