<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
	<p>{{ $initiatorFirstName }} {{ $initiatorLastName }} has marked <i>{{{ $taskName }}}</i> from the <i>{{{ $campaignTitle }}}</i> campaign as completed.</p>
	<b>Task:</b> {{{ $taskName }}}<br/>
	<b>Campaign:</b> {{{ $campaignTitle }}}<br/>
	<b>Due Date:</b> {{ $taskDueDate }}<br/>
	</body>
</html>
