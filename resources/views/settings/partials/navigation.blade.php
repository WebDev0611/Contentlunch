<ul class="panel-tabs text-center">
    <li class="{{ (Request::is('settings') ? 'active' : '') }}">
        <a  href="{{ route('settingsIndex') }}">User Settings</a>
    </li>
    <li class="{{ (Request::is('settings/connections') ? 'active' : '') }}">
        <a  href="{{ route('connectionIndex') }}">Content Connections</a>
    </li>
    <li class="{{ Request::is('settings/content') ? 'active' : '' }}">
        <a  href="{{ route('settingsBuyingIndex') }}">Content Settings</a>
    </li>
    <!--
    <li class="{{ (Request::is('settings/seo') ? 'active' : '') }}">
        <a  href="{{ route('seoIndex') }}">SEO Settings</a>
    </li>
    -->
</ul>