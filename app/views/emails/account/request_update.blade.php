<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<p>
      A request for an Account Update has been made for {{ $account->name }} on {{ $send_date }} at {{ $send_time }}
    </p>
    <p>
      {{ $account->name }}
    </p>
    <p>
      {{ $account->email }}
    </p>
    <p>
      {{ $account->phone }}
    </p>
	</body>
</html>
