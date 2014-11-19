<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
	<p>Hi {{ $accountName }},</p>
	<br/>
    <p>This is a friendly reminder that the Trial period for your {{ $tier }} subscription is coming to end. You have <i>seven days</i> remaining to use Content Launch at no cost, but in order to continue to use your subscription, please complete your account and billing information <a href="{{ URL::to('account') }}">here</a>.</p>
    <br/>
    <p>Thank you,</p>
    <br/>
    <p>Content Launch</p>
	</body>
</html>
