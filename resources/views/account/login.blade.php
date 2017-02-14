@extends('layouts.minimal')

@section('content')

    <div class="landing">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <a href="#" class="landing-logo">
                        <img src="/images/logo-full.svg" alt="#">
                    </a>
                    <div class="onboarding-container">
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1">
                                {{ Form::open(['url' => 'login']) }}
                                <h1>Login</h1>

                                @if( $errors->count() > 0 )
                                    <div class="alert alert-danger">

                                        Incorrect username/password
                                    </div>
                                @endif

                                <div class="form-group">
                                    {{ Form::label('email', 'Email Address') }}
                                    {{ Form::text('email', Input::old('email'), array('class'=>'form-control', 'placeholder' => 'awesome@awesome.com')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('password', 'Password') }}
                                    {{ Form::password('password', ['class'=>'form-control']) }}
                                </div>

                                <p>{{ Form::submit('Submit!', ['class'=>'btn btn-default']) }}</p>
                                {{ Form::close() }}


                                <div class="text-center">
                                    <a href="/signup">Register New Account</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
