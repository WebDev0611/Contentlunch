<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
	<p>Hi {{ $accountName }},</p>
	<br />
    <p>We were unable to bill your {{ $tier }} subscription. Access to your account has been temporarily disabled and in order to restore service, please login into <a href="{{ URL::to('login') }}">Content Launch</a> and update your billing information.</p>
    <br />
    <p>Thank you,</p>
    <br />
    <p>Content Launch Support Staff</p>
	</body>
</html>
