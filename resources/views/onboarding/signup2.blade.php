@extends('layouts.minimal')

@section('content')
<div class="landing">
    <div class="landing-header">
        <h1>Try us for free</h1>
        <h2>No credit card required. All features included.</h2>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="onboarding-container">
                    <a href="#" class="landing-logo">
                        <img src="/images/logo-full.svg" alt="#">
                    </a>

                    @if($errors->count()>0)
                        <div class="alert alert-danger">
                            <p>{{ $errors->first('name') }}</p>
                            <p>{{ $errors->first('email') }}</p>
                            <p>{{ $errors->first('password') }}</p>
                            <p>{{ $errors->first('company_name') }}</p>
                            <p>{{ $errors->first('account_type') }}</p>
                        </div>
                    @endif
                    {{
                        Form::open([
                            'url' => 'register',
                            'name' => 'signup_form',
                            'files' => 'true',
                            'id' => 'profile_settings',
                        ])
                    }}
                    <input type="hidden" name="redirect_url" value="/invite">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-form-group">
                                {{ Form::label('name', 'Full Name') }}
                                {{ Form::text('name', Input::old('name'), ['placeholder' => '', 'class' => 'input']) }}
                            </div>
                            <div class="input-form-group">
                                {{ Form::label('email', 'Email Address') }}
                                {{ Form::text('email', Input::old('email'), ['placeholder' => '', 'class' => 'input']) }}
                            </div>
                            <div class="input-form-group">
                                {{ Form::label('password', 'Password') }}
                                {{ Form::password('password', ['placeholder' => '','class' => 'input']) }}
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
                                {{ Form::label('company_name', 'Company Name') }}
                                {{ Form::text('company_name', Input::old('company_name'), ['placeholder' => '', 'class' => 'input']) }}
                            </div>
                            <div class="input-form-group">
                                <label class="toggle clearfix">
                                    <input type="checkbox" class="toggle-input" />
                                    <div class="toggle-switch"></div>
                                    <span class="toggle-label">Will this be an agency account?</span>
                                </label>
                            </div>
                            <div class="input-form-group">
                                {{ Form::submit('Create My Account', ['class' => 'button button-extend text-uppercase']) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="onboarding-testimonial">
                                <blockquote>
                                    <p>&ldquo;Content Launch gives us everything we need to produce awesome content and manage the workflow.&rdquo;</p>
                                    <img alt="Todd Ochsner" src="/images/todd-ochsner.jpg" />
                                    <cite>
                                        <p><strong>Todd Ochsner</strong></p>
                                        <p>Chief Idea Officer, Roni Hicks Marketing Agency</p>
                                    </cite>
                                </blockquote>
                            </div>
                            <div class="onboarding-client">
                                <h4>You're in good company</h4>
                                <p>More than 500 companies already rely on Content Launch for their content marketing software.</p>
                                <img alt="Content Launch clients" class="img-responsive" src="/images/onboarding-clients.png" />
                            </div>
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
<!-- <script src="{{ elixir('js/avatar_view.js', null) }}"></script> -->
<script src="{{ elixir('js/onboarding.js', null) }}"></script>
@stop