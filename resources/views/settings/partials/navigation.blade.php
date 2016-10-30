<ul class="panel-tabs text-center">
    <li class="{{ (Request::is('settings') ? 'active' : '') }}">
        <a  href="{{ route('settingsIndex') }}">Account Settings</a>
    </li>
    <li class="{{ (Request::is('settings/connections') ? 'active' : '') }}">
        <a  href="{{ route('connectionIndex') }}">Content Connections</a>
    </li>
    <li class="{{ Request::is('settings/content') || Request::is('settings/buying') ? 'active' : '' }}">
        <a  href="{{ route('settingsBuyingIndex') }}">Content Settings</a>
    </li>
    <!--
    <li class="{{ (Request::is('settings/seo') ? 'active' : '') }}">
        <a  href="{{ route('seoIndex') }}">SEO Settings</a>
    </li>
    -->
</ul>