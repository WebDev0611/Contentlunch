@foreach ($ideas as $idea)
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
                <a href="#"><strong>Edit Idea</strong></a>
            </li>
        </ul>
    </div>
@endforeach