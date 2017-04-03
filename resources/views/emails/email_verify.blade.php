<!DOCTYPE html>
<html lang="en-US">
<head>
    @include('emails.partials.head', ['title' => 'Verify Email Address Change'])
</head>
<body>
<h2>Content Launch: Verify Email Address Change</h2>

<div class="content">
    <div class="logo">
        <img src="{{asset('images/logo-full.svg')}}" width="215" border="0" alt="" />
    </div>
    <h3>Verify Email Address Change</h3>

    <p>
        You recently requested a user email change from your Content Launch account. <br>
        Please follow the link below to verify your new email address:
    </p>
    <a href="{{ URL::to('settings/email/verify/' . $confirmation_code) }}">
        <button>Verify address change</button>
    </a>
    <p>
        If you didn't make this request, please let us know immediately.
    </p>
    <p class="thanks">
        Thanks,<br>
        Content Launch Team
    </p>
</div>
</body>
</html>