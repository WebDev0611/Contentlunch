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
                            <p>{{ $errors->first('full_name') }}</p>
                            <p>{{ $errors->first('name') }}</p>
                            <p>{{ $errors->first('email') }}</p>
                            <p>{{ $errors->first('password') }}</p>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h3 class="onboarding-heading text-center">Welcome to ContentLaunch</h3>
                            <h5 class="onboarding-text text-center">
                                Please complete four quick steps to get started. It won’t take more than 5 mins
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h5 class="onboarding-step-text text-center">Basic information and role</h5>
                            <div class="onboarding-step">
                                <span class="onboarding-step-point active"></span>
                                <span class="onboarding-step-point"></span>
                                <span class="onboarding-step-point"></span>
                                <span class="onboarding-step-point"></span>
                            </div>
                        </div>
                    </div>
                    {{ Form::open(array('url' => 'signup')) }}
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="row">

                                <div class="col-md-8">
                                    <div class="input-form-group">
                                        {{ Form::label('full_name', 'Full Name') }}
                                        {{ Form::text('full_name', Input::old('full_name'), ['placeholder' => 'your name', 'class' => 'input']) }}
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
                                </div>
                                <div class="col-md-4">
                                    <div class="onboarding-avatar">
                                        <img src="/images/avatar.jpg" alt="#">
                                        <label for="upload" class="onboarding-avatar-button">
                                            <i class="icon-add"></i>
                                            <input id="upload" type="file">
                                            <span>Upload Avatar</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-form-group">
                                        {{ Form::label('name', 'Company Name') }}
                                        {{ Form::text('name', Input::old('name'), ['placeholder' => 'company name', 'class' => 'input']) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-form-group">
                                        <label for="#">How will you use Content Launch?</label>
                                        <div class="select">
                                            {{ Form::select('account_type', ['single'=>'To market my company', 'agency'=>'To market one or more of my clients (Agency Mode)']) }}
                                        </div>
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

<!--

<div class="row">
    <div class="col-md-4 col-md-offset-3 col-sm-10 col-sm-offset-1">
        {{ Form::open(array('url' => 'signup')) }}
        <h1>Sign Up</h1>

        @if($errors->count()>0)
            <div class="alert alert-danger">
                <p>{{ $errors->first('full_name') }}</p>
                <p>{{ $errors->first('name') }}</p>
                <p>{{ $errors->first('email') }}</p>
                <p>{{ $errors->first('password') }}</p>
            </div>
        @endif


        <div class="form-group">
            {{ Form::label('full_name', 'Full Name') }}
            {{ Form::text('full_name', Input::old('full_name'), ['placeholder' => 'your name', 'class' => 'form-control']) }}
        </div>


        <div class="form-group">
            {{ Form::label('name', 'Company Name') }}
            {{ Form::text('name', Input::old('name'), ['placeholder' => 'company name', 'class' => 'form-control']) }}
        </div>


        <div class="form-group">
            {{ Form::label('email', 'Email Address') }}
            {{ Form::text('email', Input::old('email'), ['placeholder' => 'email', 'class' => 'form-control']) }}
        </div>



        <div class="form-group">
            {{ Form::label('password', 'Password') }}
            {{ Form::password('password', ['class' => 'form-control']) }}
        </div>

        <label class="control-label">How will you use Content Launch?</label>

        <div>
            <label>
                <input type="radio"
                       name="account_type"
                       id="optHowUse1" value="single" checked>
                To market my company.
            </label>
        </div>
        <div>
            <label>
                <input type="radio" name="account_type" id="optHowUse2" value="agency" >
                To market one or more of my clients (Agency Mode)
            </label>
        </div>

        <hr/>

        <p class="center">By clicking Sign Up, you agree to our <a target="_blank" href="terms.html">Terms &amp; Conditions.</a></p>

        <p>{{ Form::submit('Sign Up', ['class' => 'btn btn-default']) }}</p>
        {{ Form::close() }}
    </div>
</div>
-->
@stop




