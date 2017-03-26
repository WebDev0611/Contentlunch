<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="utf-8">
</head>
<body>
	<h1>Content Launch Invite</h1>
    <p>Hey!</p>

    <br>

    <p>
        {{ $user->name }} has invited you to collaborate on the {{ $account->name }} account! <br>
        Content Launch is a tool to manage content marketing efforts and we think you'll benefit from using it too. <br>
        Check it out... It's free, it's easy and it has lots of cool features you can use.
    </p>

    <br>

    <p>
        Click the link below (or copy and paste on your browser location bar) to collaborate with me:
    </p>

    <br>

    <p>
        <a href="{{ $link }}">{{ $link }}</a>
    </p>
</body>
</html>
