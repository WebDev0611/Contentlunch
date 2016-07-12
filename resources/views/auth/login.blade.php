@extends('layouts.minimal')

@section('content')

    <div class="landing">
        <a href="#" class="landing-logo">
            <img src="/images/logo-full.svg" alt="#">
        </a>
        <div class="container-fluid">

            <!-- Onboarding pane -->
            <div class="onboarding-container narrow">

                <div class="inner supernarrow">
                    <h3 class="onboarding-step-text text-center">
                        Login
                    </h3>

                    <!-- Onboarding content -->
                    <div class="body">

                        <div class="input-form-group tight">
                            <label for="#">Email Address</label>
                            <div class="form-suffix form-suffix-login">
                                <i class="icon-email picto"></i>
                                <input type="email" class="input full" placeholder="Email address">
                            </div>
                        </div>

                        <div class="input-form-group tight">
                            <label for="#">Password</label>
                            <div class="form-suffix form-suffix-login">
                                <i class="icon-password picto"></i>
                                <input type="password" class="input full" placeholder="Password">
                            </div>
                        </div>


                        <div class="input-form-group tight">
                            <button type="submit" class="button button-extend button-primary text-uppercase">
                                SUBMIT
                            </button>
                        </div>


                        <div class="additional-links">
                            <p class="text-center"><a href="#" class="btn-text">Register New Account</a> | <a href="#" class="btn-text">Forgot Your Password?</a></p>
                        </div>


                    </div> <!-- End Onboarding content -->


                </div>
            </div> <!-- End Onboarding pane -->


        </div>
    </div>


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
                                <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                                    {{ csrf_field() }}

                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control" name="email"
                                                   value="{{ old('email') }}">

                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label for="password" class="col-md-4 control-label">Password</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control" name="password">

                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="remember"> Remember Me
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-btn fa-sign-in"></i> Login
                                            </button>

                                            <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your
                                                Password?</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
