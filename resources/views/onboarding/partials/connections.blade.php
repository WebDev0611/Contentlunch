<div class="col-md-4">
    <div class="col-md-12 import-tab-menu">
        <ul class="list-group">
            <li class="list-group-item active" data-group="all"> All </li>
            <li class="list-group-item" data-group="marketing"> Marketing Automation </li>
            <li class="list-group-item" data-group="social"> Social Media </li>
            <li class="list-group-item" data-group="editing-sharing"> Content Editing & Sharing </li>
        </ul>
    </div>
</div>

<div class="col-md-8 connections-list">

    <div class="col-md-12 onboarding-import-item no-padding" data-group="social">
        <div class="col-md-4">
            <div class="col-md-12">
                <img src="/images/social-icons/color-wordpress.svg" alt="WordPress" class="onboarding-import-item-img">
                <p class="onboarding-import-item-title">WordPress</p>
            </div>

            <div class="col-md-12">
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
        </div>

        @if (!$hasWordPress)
            <div
                    style='display:none'
                    id='wordPressConnectUrl'
                    data-url="{{ route('connectionProvider', [ 'wordpress', 'redirect_route' => 'onboardingConnect', 'wordpress_blog_url' => '' ]) }}"></div>

            <div class="row onboarding-import-item-additional-info" id='wordpressOnboardingInfo'>
                <div class="col-md-8">
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

    <div class="col-md-4 onboarding-import-item @if ($hasFacebook) active @endif" data-group="social">
        <div class="col-md-12">
            <img src="/images/social-icons/color-facebook.svg" alt="#" class="onboarding-import-item-img">
            <span class="onboarding-import-item-title">Facebook</span>
        </div>

        <div class="col-md-12">
            @if (!$hasFacebook)
                <a href="{{ route('connectionProvider', [
                'facebook',
                'facebook_view' => 'onboarding.connect_facebook',
                'redirect_route' => 'onboardingConnect'
            ]) }}"
                   class="button button-small">
                    Connect
                </a>
            @else
                <div class="button button-connected button-small">Connected</div>
            @endif
        </div>
    </div>

    <div class="col-md-4 onboarding-import-item @if ($hasTwitter) active @endif" data-group="social">
        <div class="col-md-12">
            <img src="/images/social-icons/color-twitter.svg" alt="#" class="onboarding-import-item-img">
            <span class="onboarding-import-item-title">Twitter</span>
        </div>
        <div class="col-md-12">
            @if (!$hasTwitter)
                <a href="{{ route('connectionProvider', [
                'twitter',
                'redirect_route' => 'onboardingConnect'
            ]) }}"
                   class="button button-small">
                    Connect
                </a>
            @else
                <div class="button button-connected button-small">Connected</div>
            @endif
        </div>
    </div>

    <div class="col-md-4 onboarding-import-item @if ($hasHubspot) active @endif" data-group="marketing">
        <div class="col-md-12">
            <img src="/images/social-icons/color-hubspot.png" alt="#" class="onboarding-import-item-img">
            <span class="onboarding-import-item-title">HubSpot</span>
        </div>

        <div class="col-md-12">
            @if (!$hasHubspot)
                <a href="{{ route('connectionProvider', [
                'hubspot',
                'redirect_route' => 'onboardingConnect'
            ]) }}"
                   class="button button-small">
                    Connect
                </a>
            @else
                <div class="button button-connected button-small">Connected</div>
            @endif
        </div>
    </div>

    <div class="col-md-4 onboarding-import-item @if ($hasLinkedIn) active @endif" data-group="social">
        <div class="col-md-12">
            <img src="/images/social-icons/color-linkedin.svg" alt="#" class="onboarding-import-item-img">
            <span class="onboarding-import-item-title">LinkedIn</span>
        </div>

        <div class="col-md-12">
            @if (!$hasLinkedIn)
                <a href="{{ route('connectionProvider', [
                'linkedin',
                'redirect_route' => 'onboardingConnect'
            ]) }}"
                   class="button button-small">
                    Connect
                </a>
            @else
                <div class="button button-connected button-small">Connected</div>
            @endif
        </div>
    </div>

    <div class="col-md-4 onboarding-import-item @if ($hasMailchimp) active @endif" data-group="marketing">
        <div class="col-md-12">
            <img src="/images/social-icons/color-mailchimp.gif" alt="#" class="onboarding-import-item-img">
            <span class="onboarding-import-item-title">Mailchimp</span>
        </div>

        <div class="col-md-12">
            @if (!$hasMailchimp)
                <a href="{{ route('connectionProvider', [
                'mailchimp',
                'redirect_route' => 'onboardingConnect'
            ]) }}"
                   class="button button-small">
                    Connect
                </a>
            @else
                <div class="button button-connected button-small">Connected</div>
            @endif
        </div>
    </div>

    <div class="col-md-4 onboarding-import-item @if ($hasDropbox) active @endif" data-group="editing-sharing">
        <div class="col-md-12">
            <img src="/images/social-icons/color-dropbox.svg" alt="#" class="onboarding-import-item-img">
            <span class="onboarding-import-item-title">Dropbox</span>
        </div>

        <div class="col-md-12">
            @if (!$hasDropbox)
                <a href="{{ route('connectionProvider', [
                'dropbox',
                'redirect_route' => 'onboardingConnect'
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