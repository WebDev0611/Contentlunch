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
                                These are the places you can distribute content to. <br>
                                Connect now or do this step later.
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
                        <div class="col-md-10 col-md-offset-1">
                            <div class="onboarding-import">
                                <ul class="onboarding-import-menu">
                                    <li class="active">
                                        <a href="#connections" data-toggle="tab">Content Connections</a>
                                    </li>
                                </ul>

                                <div class="onboarding-import-container tab-content">
                                    <div id="connections" class="onboarding-import-list tab-pane active">
                                        @include('onboarding.partials.connections')

                                        <div class="clearfix"></div>
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

                    <div class="row later">
                        <div class="col-md-6 col-md-offset-3 margin-20">
                            <div class="text-center">
                                <a href="{{ url('score') }}">Do this step later</a>
                            </div>
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

        $(".import-tab-menu ul.list-group > li").click(function (e) {
            $(this).siblings('li.active').removeClass("active");
            $(this).addClass("active");

            var group = $(this).data('group');
            var connectionItem = $('.connections-list > .onboarding-import-item');
            connectionItem.fadeIn();

            if(group == 'all') {
                connectionItem.removeClass("hidden-item");
            } else {
                connectionItem.addClass("hidden-item");
                $('.connections-list > .onboarding-import-item[data-group="' + group + '"]').removeClass("hidden-item");
            }

            $('.hidden-item').hide();
        });
    })();
</script>
@endsection
