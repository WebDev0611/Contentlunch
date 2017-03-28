<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
    <h2>Notification: WriterAccess API error occurred</h2>
    <p>
        This is an automatic email notification from Content Launch app triggered by WriterAccess error when trying to place the order.
        <br><br>
        <b>Order details:</b> <br>
        User name: {{ $data['user_name'] }} <br>
        User email: {{ $data['user_email'] }} <br>
        Account ID: {{ $data['acc_id'] }} <br>
        Timestamp: {{ date('M-d-Y H:i:s') }} <br>
        WriterAccess response: {{ $data['api_response'] }} <br>
    </p>
</body>
</html>
