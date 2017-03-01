<div class="dashboard-tasks-container dashboard-container-borders">
    <div class="dashboard-tasks-cell cell-size-5">
        <img src="{{ $campaign->user->present()->profile_image }}" alt="#" class="dashboard-tasks-img">
    </div>
    <div class="dashboard-tasks-cell cell-size-55">
        <h5 class="dashboard-tasks-title">
            <a href="/campaign/{{ $campaign->id }}">
                {{ $campaign->title }}
            </a>
        </h5>
        <ul class="dashboard-tasks-list">
            <li>UPDATED: <strong>{{ strtoupper($campaign->updated_at_diff) }}</strong></li>
        </ul>
    </div>
    <div class="dashboard-tasks-cell cell-size-20">
        <ul class="dashboard-tasks-list">
            <li>
                STARTED: <br />
                <strong>{{ strtoupper($campaign->started) }}</strong>
            </li>
        </ul>
    </div>
    <div class="dashboard-tasks-cell cell-size-20">
        <div class="dashboard-tasks-list">
            <li>
                ENDING: <br />
                <strong>{{ strtoupper($campaign->ending) }}</strong>
            </li>
        </div>
    </div>
    <div></div>
</div>