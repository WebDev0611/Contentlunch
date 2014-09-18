<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<p>This email is to notify you that there is a brainstorm session "{{ $brainstorm['description'] }}" scheduled for  {{ date('F j, Y \a\t g:i a', strtotime($brainstorm['datetime'])) }}. </p>

<p>Content Launch Team</p>

</body>
</html>