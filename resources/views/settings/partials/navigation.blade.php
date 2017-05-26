<ul class="panel-tabs text-center">
    <li class="{{ (Request::is('settings') ? 'active' : '') }} panel-settings">
        <a href="{{ route('settings.index') }}">User Settings</a>
    </li>
    <li class="{{ (Request::is('settings/connections') ? 'active' : '') }} panel-connection-settings">
        <a href="{{ route('connections.index') }}">Content Connections</a>
    </li>
    <li class="{{ Request::is('settings/buying') || Request::is('settings/content') ? 'active' : '' }} panel-content-settings">
        <a href="{{ route('buying_settings.index') }}">Content Settings</a>
    </li>
    <li class="{{ Request::is('settings/account') ? 'active' : '' }} panel-account-settings">
        <a href="{{ route('settingsAccount') }}">Account Settings</a>
    </li>
    <li class="{{ Request::is('settings/subscription') ? 'active' : '' }} panel-subscription">
        <a href="{{ route('subscription') }}">Subscription</a>
    </li>
    @if(isset($account) && ($account->isAgencyAccount() || $account->isSubAccount()))
    <li class="{{ (Request::is('settings/subscription/clients') ? 'active' : '') }}">
        <a href="{{ route('subscription-clients') }}">Clients subscriptions</a>
    </li>
    @endif
                <!--
    <li class="{{ (Request::is('settings/seo') ? 'active' : '') }}">
        <a  href="{{ route('seo_settings.index') }}">SEO Settings</a>
    </li>
    -->
</ul>