<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Content Launch: Verify Email Address Change</h2>

<div>
    You recently requested a user email change from your Content Launch account. <br><br>
    Please follow the link below to verify your new email address: <br>
    <a href="{{ URL::to('settings/email/verify/' . $confirmation_code) }}">{{ URL::to('settings/email/verify/' . $confirmation_code) }}</a><br><br>
    If you didn't make this request, please let us know immediately.<br><br>
    Thanks,<br>
    Content Launch Team

</div>

</body>
</html>