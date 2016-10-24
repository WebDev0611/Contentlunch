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
                                        <div class="onboarding-import-item">
                                            <div class="col-md-6">
                                                <img src="/images/avatar.jpg" alt="#" class="onboarding-import-item-img">
                                                <span class="onboarding-import-item-title">WordPress</span>
                                            </div>

                                            <div class="col-md-6 text-right">
                                                @if (!$hasWordPress)
                                                <a href
                                                   id='wordpress_connect_button'
                                                   class="button button-small">
                                                    Connect
                                                </a>
                                                @else
                                                <div class="button button-connected button-small">Connected</div>
                                                @endif
                                            </div>

                                            @if (!$hasWordPress)
                                            <div
                                                style='display:none'
                                                id='wordPressConnectUrl'
                                                data-url="{{ route('connectionProvider', [ 'wordpress', 'redirect_route' => 'onboardingConnect', 'wordpress_blog_url' => '' ]) }}"></div>

                                            <div class="row onboarding-import-item-additional-info" id='wordpressOnboardingInfo'>
                                                <div class="col-md-12">
                                                    <div class="input-form-group">
                                                        <label for="api_url">Wordpress URL</label>
                                                        <input type="text" id='wordpressUrl' class="input">
                                                        <p class="help-block">wordpressdomain.com</p>
                                                    </div>
                                                    <div class="alert alert-danger alert-no-margin" style='display:none' id='wordPressBlogFeedback'>
                                                        Please enter a valid Wordpress blog url
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="connections" class="onboarding-import-list tab-pane active">
                                        <div class="onboarding-import-item @if ($hasFacebook) active @endif">
                                            <div class="col-md-6">
                                                <img src="/images/avatar.jpg" alt="#" class="onboarding-import-item-img">
                                                <span class="onboarding-import-item-title">Facebook</span>
                                            </div>

                                            <div class="col-md-6 text-right">
                                                @if (!$hasFacebook)
                                                <a  href="{{ route('connectionProvider', [
                                                        'facebook',
                                                        'facebook_view' => 'onboarding.connect_facebook',
                                                        'redirect' => 'onboardingConnect'
                                                    ]) }}"
                                                    class="button button-small">
                                                    Connect
                                                </a>
                                                @else
                                                <div class="button button-connected button-small">Connected</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="onboarding-import-item @if ($hasTwitter) active @endif">
                                            <div class="col-md-6">
                                                <img src="/images/avatar.jpg" alt="#" class="onboarding-import-item-img">
                                                <span class="onboarding-import-item-title">Twitter</span>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                @if (!$hasTwitter)
                                                <a  href="{{ route('connectionProvider', [
                                                        'twitter',
                                                        'redirect' => 'onboardingConnect'
                                                    ]) }}"
                                                    class="button button-small">
                                                    Connect
                                                </a>
                                                @else
                                                <div class="button button-connected button-small">Connected</div>
                                                @endif
                                            </div>
                                        </div>
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
