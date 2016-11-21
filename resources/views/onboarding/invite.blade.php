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
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <h5 class="onboarding-step-text text-center">
                                    Invite team members with whom you will collaborate
                                </h5>
                                <div class="onboarding-step">
                                    <span class="onboarding-step-point active"></span>
                                    <span class="onboarding-step-point active"></span>
                                    <span class="onboarding-step-point"></span>
                                    <span class="onboarding-step-point"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="onboarding-connect">
                                    <ul class="onboarding-connect-menu">
                                        <!--
                                        <li >
                                            <a href="#fb" data-toggle="tab">
                                                <i class="icon-facebook-mini"></i>
                                                Facebook
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#tw" data-toggle="tab">
                                                <i class="icon-twitter2"></i>
                                                Twitter
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#ln" data-toggle="tab">
                                                <i class="icon-linkedin-mini"></i>
                                                LinkedIn
                                            </a>
                                        </li>
                                        -->
                                        <li class="active">
                                            <a href="#em" data-toggle="tab">
                                                <i class="icon-envelope"></i>
                                                Email
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="onboarding-connect-container tab-content">
                                        <!--
                                        <div id="fb" class="onboarding-connect-section tab-pane ">
                                            <div class="onboarding-connect-section-backdrop">
                                                <div class="onboarding-connect-section-backdrop-content">
                                                    <p class="onboarding-text">Please connect to Facebook to invite your friends to collaborate</p>
                                                    <a href="{{ URL::to('invite/redirect') }}" class="button button-social facebook">
                                                        <i class="icon-facebook-mini"></i>
                                                        CONNECT
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="tw" class="onboarding-connect-section tab-pane">
                                            <div class="onboarding-connect-section-backdrop-content">
                                                <p class="onboarding-text">Please connect to Facebook to invite your friends to collaborate</p>
                                                <a href="#" class="button button-social twitter">
                                                    <i class="icon-twitter2"></i>
                                                    CONNECT
                                                </a>
                                            </div>
                                        </div>
                                        <div id="ln" class="onboarding-connect-section tab-pane">
                                            <div class="onboarding-connect-section-backdrop-content">
                                                <p class="onboarding-text">Please connect to Facebook to invite your friends to collaborate</p>
                                                <a href="#" class="button button-social linkedin">
                                                    <i class="icon-linkedin"></i>
                                                    CONNECT
                                                </a>
                                            </div>
                                        </div>
                                        -->

                                        <div id="em" class="onboarding-connect-section tab-pane active">
                                            <div class="onboarding-connect-section-backdrop-content">

                                                @if ($errors->any())
                                                    <div  class="alert alert-danger">
                                                        <p><strong>Error:</strong>
                                                            @foreach($errors->all() as $error)
                                                                {{ $error }}
                                                            @endforeach
                                                        </p>
                                                    </div>
                                                @endif


                                                {{ Form::open(array('url' => 'invite/emails')) }}
                                                    <p class="onboarding-text">Invite friends by email</p>
                                                    <div class="form-group">
                                                        <input type="text"
                                                            class="input input-tertiary"
                                                            name="emails"
                                                            placeholder="Enter comma delimited email addresses">
                                                    </div>
                                                    {{
                                                        Form::submit('SEND INVITE(S)', [
                                                            'class' => 'button button-extend text-uppercase'
                                                        ])
                                                    }}
                                                {{ Form::close() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <a href="{{ url('connect') }}"
                                    class="button button-extend text-uppercase">Next Step</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop




