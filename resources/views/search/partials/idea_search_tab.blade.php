@forelse ($ideas as $idea)
    <div class="search-item">
        <h5 class="dashboard-tasks-title">
            {{ $idea->name }}
        </h5>
        <span class="dashboard-tasks-text">
            {{ $idea->text ?: "No description provided" }}
        </span>
        <ul class="dashboard-tasks-list">
            <li>
                STATUS: <strong>{{ strtoupper($idea->status) }}</strong>
            </li>
            <li>
                <a href="{{ route('ideaEditor', $idea->id) }}"><strong>Edit Idea</strong></a>
            </li>
        </ul>
    </div>
@empty
    <div class="alert alert-info alert-forms">
        No ideas found with the search term <strong>{{ $searchTerm }}</strong>
    </div>
@endforelse