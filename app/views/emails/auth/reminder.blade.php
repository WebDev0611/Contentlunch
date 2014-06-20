<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>

    <div>
      You are recieving this email because you completed the Password Reset form at contentlaunch.com
    </div>
    <br />

    <div>
      Please click the link below to complete the password reset process. This link will expire in 24 hours.
    </div>
    <br />

		<div>
			<a href="{{ URL::to('password/reset', array($token)) }}">Reset Password</a>
		</div>
    <br />

    <div>
      Thank You!
    </div>
    <br />

    <div>
      Content Launch Support Staff
    </div>
    <br />

	</body>
</html>
