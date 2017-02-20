<div class="panel-header">
    <ul class="panel-tabs spacing">
        <li class="{{ !Request::segment(2) ? 'active' : '' }}">
            <a href="/content">All Content</a>
        </li>
        <li class="{{ Request::segment(2) === 'orders' ? 'active' : '' }}">
            <a href="/content/orders">Writing Orders in Process</a>
        </li>
        <li class="{{ Request::segment(2) === 'campaigns' ? 'active' : '' }}">
            <a href="/content/campaigns">Campaigns</a>
        </li>
    </ul>
</div>