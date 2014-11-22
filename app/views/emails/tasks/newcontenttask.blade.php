<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
	<p>Hi {{ $assigneeFirstName }},</p>
    <p>You have been assigned a new task on the <i>{{ $contentTitle }}</i> content.</p>
	<b>Task:</b> {{{ $taskName }}}<br/>
	<b>Content:</b> {{{ $contentTitle }}}<br/>
	<b>Due Date:</b> {{ $taskDueDate }}<br/>
	</body>
</html>
