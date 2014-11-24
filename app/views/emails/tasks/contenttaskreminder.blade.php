<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
	<p>Hi {{ $assigneeFirstName }},</p>
    <p>This is a reminder that the <i>{{{ $taskName }}}</i> task on the {{{ $contentTitle }}} content is due tomorrow.</p>
	<b>Task:</b> {{{ $taskName }}}<br/>
	<b>Content:</b> {{{ $contentTitle }}}<br/>
	<b>Due Date:</b> {{ $taskDueDate }}<br/>
	</body>
</html>
