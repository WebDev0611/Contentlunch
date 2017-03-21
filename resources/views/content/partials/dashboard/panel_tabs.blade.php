<div class="panel-header">
    <ul class="panel-tabs spacing">
        <li class="{{ !Request::segment(2) ? 'active' : '' }}">
            <a href="{{ route('contents.index') }}">All Content</a>
        </li>
        <li class="{{ Request::segment(2) === 'orders' ? 'active' : '' }}">
            <a href="{{ route('content_orders.index') }}">Writing Orders in Process</a>
        </li>
        <li class="{{ Request::segment(2) === 'campaigns' ? 'active' : '' }}">
            <a href="{{ route('campaigns.index') }}">Campaigns</a>
        </li>
    </ul>
</div>