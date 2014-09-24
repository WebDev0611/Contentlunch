<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<p>Greetings from Content Launch</p>

<p>This is to notify you that a brainstorm session has been rescheduled for the concept {{ $concept['title'] }}</p>

<p>on {{ date('F j, Y \a\t g:i a', strtotime($brainstorm['datetime'])) }}</p>

<p>To see more information, please login here <a href="{{ URL::to('login') }}">{{ URL::to('login') }}</a></p>

</body>
</html>