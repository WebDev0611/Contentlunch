<ul class="panel-tabs text-center">
    <li class="{{ (Request::is('settings') ? 'active' : '') }}">
        <a href="{{ route('settingsIndex') }}">User Settings</a>
    </li>
    <li class="{{ (Request::is('settings/connections') ? 'active' : '') }}">
        <a href="{{ route('connectionIndex') }}">Content Connections</a>
    </li>
    <li class="{{ Request::is('settings/buying') ? 'active' : '' }}">
        <a href="{{ route('settingsBuyingIndex') }}">Content Settings</a>
    </li>
    <li class="{{ Request::is('settings/account') ? 'active' : '' }}">
        <a href="{{ route('settingsAccount') }}">Account Settings</a>
    </li>
    <li class="{{ Request::is('settings/subscription') ? 'active' : '' }}">
        <a href="{{ route('subscription') }}">Subscription</a>
    </li>
    @if($account->isAgencyAccount() || $account->isSubAccount())
        <li class="{{ (Request::is('settings/subscription/clients') ? 'active' : '') }}">
            <a href="{{ route('subscription-clients') }}">Clients subscriptions</a>
        </li>
        @endif
                <!--
    <li class="{{ (Request::is('settings/seo') ? 'active' : '') }}">
        <a  href="{{ route('seoIndex') }}">SEO Settings</a>
    </li>
    -->
</ul>