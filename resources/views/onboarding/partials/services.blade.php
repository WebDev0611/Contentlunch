
<div class="onboarding-import-item">
    <div class="col-md-6">
        <img src="/images/cl-avatar2.png" alt="#" class="onboarding-import-item-img">
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