<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<p>This email is to notify you that the brainstorm session "{{ $brainstorm['description'] }}" has been rescheduled to {{ date('F j, Y \a\t g:i a', strtotime($brainstorm['datetime'])) }}. </p>

<p>Content Launch Team</p>

</body>
</html>