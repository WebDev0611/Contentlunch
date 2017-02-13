@extends('layouts.minimal')

@section('content')
<div class="landing">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="onboarding-container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">

                            <div class="landing-hero four-oh-four">
                                <img src="/images/404.png" alt="404"/>
                            </div>

                            <h3 class="onboarding-heading text-center">Ups, something went wrong...</h3>
                            <h5 class="onboarding-text text-center">
                                We can't seem to find the page you are looking for. Maybe it is best you go back to home
                            </h5>
                            <p>&nbsp;</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <a href="/" class="button button-extend text-uppercase">Back to home</a>
                        </div>
                    </div>
                    <p>&nbsp;</p>
                    <div class="row">
                        <a href="#" class="landing-logo bottom">
                            <img src="/images/logo-full.svg" alt="#">
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@stop
