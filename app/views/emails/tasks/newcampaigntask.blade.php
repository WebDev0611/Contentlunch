<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
	<p>Hi {{ $firstName }},</p>
    <p>{{ $accountName }} has assigned you a new task on the <i>{{ $campaignTitle }}</i> campaign.</p>
    <p>Task: <b>{{ $taskName }}</b></p>
    <p>Due Date: <b>{{ $dueDate }}</b></p>
    <p>Campaign Description: {{ $campaignDesc }}</p>
	</body>
</html>
