@forelse ($campaigns as $campaign)
    <div class="search-item">
        <h5 class="dashboard-tasks-title">
            {{ $campaign->title }}
        </h5>
        <span class="dashboard-tasks-text">
            @if ($campaign->description)
                {{ $campaign->description }}
            @else
                No description provided
            @endif
        </span>
        <ul class="dashboard-tasks-list">
            <li>
                STATUS: <strong>{{ array_flip((new \ReflectionClass(\App\Campaign::class))->getConstants())[$campaign->status] }}</strong>
            </li>
            <li>
                <a href="{{ route('campaigns.edit', $campaign->id) }}"><strong>Edit Campaign</strong></a>
            </li>
        </ul>
    </div>
@empty
    <div class="alert alert-info alert-forms">
        No campaigns found with the search term <strong>{{ $searchTerm }}</strong>
    </div>
@endforelse