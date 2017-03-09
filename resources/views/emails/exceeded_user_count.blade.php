<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
    <h2>User limit reached</h2>
    <p>
        Hey! An invited user, with the email {{ $email }}, just tried to join your team but couldn't because
        your account has reached its maximum users' limit.
    </p>
    <p>
        <a href="{{ route('subscription') }}">Upgrade now to increase your limits.</a>
    </p>
</body>
</html>
