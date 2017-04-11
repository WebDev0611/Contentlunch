<div class="panel-header">
    <ul class="panel-tabs spacing">
        <li class="{{ !Request::segment(2) ? 'active' : '' }} panel-tabs-all-content">
            <a href="{{ route('contents.index') }}">All Content</a>
        </li>
        <li class="{{ Request::segment(2) === 'orders' ? 'active' : '' }} panel-tabs-writing-orders">
            <a href="{{ route('content_orders.index') }}">Writing Orders in Process</a>
        </li>
        <li class="{{ Request::segment(2) === 'campaigns' ? 'active' : '' }} panel-tabs-campaigns">
            <a href="{{ route('campaigns.index') }}">Campaigns</a>
        </li>
        <li class="{{ Route::currentRouteName() === 'archived_contents.index' ? 'active' : '' }} panel-tabs-archive">
            <a href="{{ route('archived_contents.index') }}">Archive</a>
        </li>
    </ul>
</div>