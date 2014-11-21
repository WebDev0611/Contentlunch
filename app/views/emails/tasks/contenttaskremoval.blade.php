<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
	<p>Hi {{ $firstName }},</p>
    <p>You have been removed from a task on the <i>{{ $contentTitle }}</i> content, which was due on {{ $dueDate }}.</p>
    <p>Task Name: <b>{{ $taskName }}</b></p>
	</body>
</html>
