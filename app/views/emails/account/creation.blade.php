<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Welcome to <img src="<?php echo $message->embed('assets/images/logo.png') ?>" style="max-height: 75px" alt="Content Launch"></h2>

		<p>
      A new Content Launch account has been created for you. Please click the link below to complete the registration process. This link will expire in 24 hours.
    </p>
    <p>
      <a href="{{ URL::to('user/confirm', array($token)) }}">Content Launch Account</a>
		</p>
    <p>
      Thank You!
    </p>
    <p>
      Content Launch Support Staff
    </p>
	</body>
</html>
