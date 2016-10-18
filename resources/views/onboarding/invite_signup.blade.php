@extends('layouts.minimal')
@section('content')
<div class="landing">
    <a href="#" class="landing-logo">
        <img src="/images/logo-full.svg" alt="#">
    </a>
    <div class="container-fluid">
        {{ Form::open([ 'url' => 'signup/invite' ]) }}
        <!-- Onboarding pane -->
        <div class="onboarding-container">
            @if($errors->count()>0)
                <div class="alert alert-danger">
                    <p>{{ $errors->first('name') }}</p>
                    <p>{{ $errors->first('email') }}</p>
                    <p>{{ $errors->first('password') }}</p>
                </div>
            @endif
            <div class="inner narrow">
                <h3 class="text-center">
                    Welcome to ContentLaunch
                </h3>
                <h5 class="onboarding-text text-center">
                    Please enter your information to create an account.
                </h5>
                <!-- Onboarding content -->
                <div class="body">
                    {{ Form::hidden('account_id', $invite->account_id) }}

                    <div class="input-form-group">
                        {{ Form::label('name', 'Full Name') }}
                        {{
                            Form::text('name', Input::old('name'), [
                                'placeholder' => 'Your Name',
                                'class' => 'input full'
                            ])
                        }}
                    </div>
                    <div class="input-form-group">
                        {{ Form::label('email', 'Email') }}
                        {{
                            Form::text('email', $invite->email, [
                                'placeholder' => 'Your Email',
                                'class' => 'input full'
                            ])
                        }}
                    </div>
                    <div class="input-form-group">
                        {{ Form::label('password', 'Password') }}
                        {{
                            Form::password('password', [
                                'placeholder' => 'Password',
                                'class' => 'input full'
                            ])
                        }}
                    </div>
                    <div class="input-form-group">
                        {{ Form::label('confirm_password', 'Confirm your password') }}
                        {{
                            Form::password('confirm_password', [
                                'placeholder' => 'Confirm your password',
                                'class' => 'input full'
                            ])
                        }}
                    </div>
                    <div class="input-form-group">
                        {{ Form::submit('Create My Account', ['class' => 'button button-extend text-uppercase']) }}
                    </div>
                </div> <!-- End Onboarding content -->
            </div>
        </div> <!-- End Onboarding pane -->
        {{ Form::close() }}
    </div>
</div>
@stop