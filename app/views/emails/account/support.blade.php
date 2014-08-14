<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
    <p>
        Someone filled out the customer support email:
    </p>

    <p>
        <strong>Email Address</strong><br>
        {{ $email }}
    </p>
    <p>
        <strong>Name</strong><br>
        {{ $name }}
    </p>
    <p>
        <strong>Company</strong><br>
        {{ $company }}
    </p>
    <p>
        <strong>Which Module are you experiencing trouble with?</strong><br>
        {{ $module }}
    </p>
    <p>
        <strong>Please describe your problem.</strong><br>
        {{ $problem }}
    </p>
</body>
</html>
