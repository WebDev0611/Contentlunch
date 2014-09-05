<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<p>{{ $conference->user->first_name }} {{ $conference->user->last_name }} from {{ $conference->account->name }} has requested a video conference. The information below was given in the request: </p>

<p>
    {{ $conference->user->first_name }} {{ $conference->user->last_name }} <br/>
    {{ $conference->user->title }} <br/>
    {{ $conference->user->email }} <br/>
    {{ $conference->account->name }}
</p>

<p>
    Request Description <br/>
    {{ $conference->description }} <br/>
    Topic <br/>
    {{ $conference->topic }} <br/>
    Schedule Option 1 <br/>
    {{ date('F j, Y \a\t g:i a', strtotime($conference->date_1)) }} <br/>
    Schedule Option 2 <br/>
    {{ date('F j, Y \a\t g:i a', strtotime($conference->date_2)) }} <br/>
    Other <br/>
    {{ $conference->date_other_comment }}
</p>

</body>
</html>