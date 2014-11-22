<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
	<p>{{ $initiatorFirstName }} {{ $initiatorLastName }} has marked <i>{{{ $taskName }}}</i> from the <i>{{{ $contentTitle }}}</i> content as completed.</p>
	<b>Task:</b> {{{ $taskName }}}<br/>
	<b>Content:</b> {{{ $contentTitle }}}<br/>
	<b>Due Date:</b> {{ $taskDueDate }}<br/>
	</body>
</html>
