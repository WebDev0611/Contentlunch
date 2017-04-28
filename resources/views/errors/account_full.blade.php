@extends('layouts.minimal')

@section('content')
<div class="landing">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="onboarding-container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">

                            <h3 class="onboarding-heading text-center">
                                This account has reached its maximum user limit
                            </h3>
                            <h5 class="onboarding-text text-center">
                                This account's current plan does not support any more users.
                            </h5>
                            <p>&nbsp;</p>
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
