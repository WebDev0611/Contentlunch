<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
	<p>Hi {{ $firstName }},</p>
	@if ($orignalTaskName == $taskName && $orignalTaskDueDate != $taskDueDate)
		<p>The due date for the task <b>{{ $taskName }}</b> from the <i>{{ $contentTitle }}</i> content has been change from {{ $orignalTaskDueDate }} to <b>{{ $taskDueDate }}.</b></p>
	@elseif ($orignalTaskName != $taskName && $orignalTaskDueDate == $taskDueDate)
		<p>The task <i>{{ $orignalTaskName }}</i> from the <i>{{ $contentTitle }}</i> content has been changed to <b>{{{ $taskName }}}</b>. The due date remains the same at {{ $taskDueDate }}.</i></p>
	@elseif ($orignalTaskName != $taskName && $orignalTaskDueDate != $taskDueDate)
		<p>The task <i>{{ $orignalTaskName }}</i> from the <i>{{ $contentTitle }}</i> content has been changed to <b>{{{ $taskName }}}</b>. The due date has also changed from {{ $orignalTaskDueDate }} to <b>{{ $taskDueDate }}.</b></p>
	@endif
	</body>
</html>
