<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="utf-8">
</head>
<body>
    <h2>Assigned to you: {{ $task->name }}</h2>
    <p>
        Task: <a href="{{ route('tasks.edit', $task->id) }}">{{ $task->name }}</a>
        Due date: {{ $task->present()->dueDateFormat() }}
        Description: {{ $task->explanation }}
    </p>
</body>
</html>
