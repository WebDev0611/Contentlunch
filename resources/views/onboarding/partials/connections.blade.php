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

<div class="onboarding-import-item @if ($hasTwitter) active @endif">
    <div class="col-md-6">
        <img src="/images/avatar.jpg" alt="#" class="onboarding-import-item-img">
        <span class="onboarding-import-item-title">Twitter</span>
    </div>
    <div class="col-md-6 text-right">
        @if (!$hasTwitter)
        <a  href="{{ route('connectionProvider', [
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