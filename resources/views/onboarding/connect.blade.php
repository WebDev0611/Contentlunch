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
                                Configure your content connections
                            </h5>
                            <h5 class="onboarding-text text-center">
                                Connect as many services you use. Based on information from your social
                                and publishing platforms we calculate your CL score
                            </h5>
                            <div class="onboarding-step">
                                <span class="onboarding-step-point active"></span>
                                <span class="onboarding-step-point active"></span>
                                <span class="onboarding-step-point active"></span>
                                <span class="onboarding-step-point"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="onboarding-import">
                                <ul class="onboarding-import-menu">
                                    <li>
                                        <a href="#services" data-toggle="tab">Import Services</a>
                                    </li>
                                    <li class="active">
                                        <a href="#connections" data-toggle="tab">Content Connections</a>
                                    </li>
                                </ul>

                                <div class="onboarding-import-container tab-content">
                                    <div id="services" class="onboarding-import-list tab-pane">
                                        @include('onboarding.partials.services'
)                                    </div>
                                    <div id="connections" class="onboarding-import-list tab-pane active">
                                        @include('onboarding.partials.connections')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <a href="{{ url('score') }}"
                               class="button button-extend text-uppercase">

                               Next Step
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
<script>
    (function() {

        $('#wordpress_connect_button').click(handleWordPressConnect);
        $('#wordpressUrl').on('keyup keypress keydown', hideErrorFeedbacks);

        function handleWordPressConnect(event) {
            event.stopPropagation();
            event.preventDefault();

            var wordPressUrl = $('#wordPressConnectUrl').data('url');
            var blogUrl = $('#wordpressUrl').val();

            if (blogUrl) {
                document.location.href = wordPressUrl + blogUrl;
            } else {
                showErrorFeedbacks();
            }
        }

        function showErrorFeedbacks() {
            $('#wordPressBlogFeedback').slideDown('fast');
        }

        function hideErrorFeedbacks() {
            $('#wordPressBlogFeedback').slideUp('fast');
        }
    })();
</script>
@endsection
