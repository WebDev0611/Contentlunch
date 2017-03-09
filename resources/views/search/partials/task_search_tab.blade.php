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
                <strong>{{ $task->present()->dueDateFormat }}</strong>
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