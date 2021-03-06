@extends('layouts.minimal')

@section('content')
<div class="landing">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="onboarding-container">
                    <a href="#" class="landing-logo">
                        <img src="/images/logo-full.svg" alt="#">
                    </a>
                    @include('partials.error')

                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h3 class="onboarding-heading text-center">Welcome to {{ trans('messages.company') }}</h3>
                            <h5 class="onboarding-text text-center">
                                You have been invited as a client to the {{ $accountName }} account.
                                Please complete the form below to get started.
                            </h5>
                        </div>
                    </div>

                    {{
                        Form::open([
                            'route' => [ 'guests.store', $guestInvite ],
                            'name' => 'signup_form',
                            'files' => 'true',
                            'id' => 'profile_settings',
                        ])
                    }}
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="row">
                                <div class="col-md-12">
                                    @include('guests.partials.form')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            {{ Form::submit('Review Content', ['class' => 'button button-extend text-uppercase']) }}
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
<script src="{{ elixir('js/avatar_view.js', null) }}"></script>
<script src="{{ elixir('js/onboarding.js', null) }}"></script>
@stop
