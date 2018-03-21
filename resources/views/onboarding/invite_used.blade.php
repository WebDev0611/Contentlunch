@extends('layouts.minimal')
@section('content')
<div class="landing">
    <div class="container-fluid">
        <!-- Onboarding pane -->
        <div class="onboarding-container">
            <a href="#" class="landing-logo">
                <img src="/images/logo-full.svg" alt="#">
            </a>
            <div class="inner narrow">
                <h3 class="text-center">
                    Welcome to {{ trans('messages.company') }}
                </h3>
                <h5 class="onboarding-text text-center">
                    Please enter your information to create an account.
                </h5>
            </div>
            <div class="body">
                <div class="alert alert-danger">
                    This invite was already used.
                </div>
            </div>
        </div> <!-- End Onboarding pane -->
    </div>
</div>
@stop