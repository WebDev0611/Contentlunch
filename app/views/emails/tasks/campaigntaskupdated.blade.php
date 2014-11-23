<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
	<p>Hi {{ $assigneeFirstName }},</p>
	@if ($orignalTaskName == $taskName && $orignalTaskDueDate != $taskDueDate)
		<p>The due date for <i>{{ $taskName }}</i> on the <i>{{ $campaignTitle }}</i> content has been change from {{ $orignalTaskDueDate }} to {{ $taskDueDate }}.</p>
	@elseif ($orignalTaskName != $taskName && $orignalTaskDueDate == $taskDueDate)
		<p>The task <i>{{ $orignalTaskName }}</i> on the <i>{{ $campaignTitle }}</i> content has been changed to <i>{{{ $taskName }}}</i>. The due date remains the same at {{ $taskDueDate }}.</i></p>
	@elseif ($orignalTaskName != $taskName && $orignalTaskDueDate != $taskDueDate)
		<p>The task <i>{{ $orignalTaskName }}</i> on the <i>{{ $campaignTitle }}</i> content has been changed to <i>{{{ $taskName }}}</i>. The due date has also changed from {{ $orignalTaskDueDate }} to {{ $taskDueDate }}.</p>
	@endif
	<b>Task:</b> {{{ $taskName }}}<br/>
	<b>Campaign:</b> {{{ $campaignTitle }}}<br/>
	<b>Due Date:</b> {{ $taskDueDate }}<br/>
	</body>
</html>
