@forelse ($tasks as $task)
    <div class="search-item">
        <h5 class="dashboard-tasks-title">
            {{ $task->name }}
        </h5>
        <span class="dashboard-tasks-text">
            @if ($task->explanation)
                {{ $task->explanation }}
            @else
                No explanation provided
            @endif
        </span>
        <ul class="dashboard-tasks-list">
            <li>
                DUE IN:
                @if ($task->due_date != "0000-00-00 00:00:00")
                    {{ $dateObject = new \Carbon\Carbon($task->due_date) }}
                    <strong>{{ strtoupper($dateObject->diffForHumans()) }}</strong>
                @else
                    <strong>NO DUE DATE PROVIDED</strong>
                @endif
            </li>
            <li>
                <a href="{{ route('taskShow', $task->id) }}"><strong>Edit Task</strong></a>
            </li>
        </ul>
    </div>
@empty
    <div class="alert alert-info alert-forms">
        No tasks found with the search term <strong>{{ $searchTerm }}</strong>
    </div>
@endforelse