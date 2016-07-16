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
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                            {{ csrf_field() }}
                            <div class="input-form-group tight {{ $errors->has('email') ? 'has-error' : '' }}">
                                <label for="#">Email Address</label>
                                <div class="form-suffix form-suffix-login">
                                    <i class="icon-email picto"></i>
                                    <input  id="email"  name="email" type="email" class="input full" placeholder="Email address" value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                    @endif
                                </div>
                            </div>

                            <div class="input-form-group tight {{ $errors->has('password') ? 'has-error' : '' }}">
                                <label for="#">Password</label>
                                <div class="form-suffix form-suffix-login">
                                    <i class="icon-password picto"></i>

                                    <input id="password" type="password" class="input full" name="password">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>


                            <div class="input-form-group tight">
                                <label>
                                    <input type="checkbox" name="remember"> &nbsp; Remember Me
                                </label>
                            </div>


                            <div class="input-form-group tight">
                                <button type="submit" class="button button-extend button-primary text-uppercase">
                                    SUBMIT
                                </button>
                            </div>


                            <div class="additional-links">
                                <p class="text-center"><a href="/signup" class="btn-text">Register New Account</a> | <a href="/password/reset" class="btn-text">Forgot Your Password?</a></p>
                            </div>

                        </form>
                    </div> <!-- End Onboarding content -->


                </div>
            </div> <!-- End Onboarding pane -->


        </div>
    </div>
@endsection
