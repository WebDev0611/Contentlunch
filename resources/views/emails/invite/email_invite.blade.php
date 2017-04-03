@php
    $staticData = ['title' => 'Content Launch Invite'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>Hey!</p>
    <p>
        {{ $user->name }} has invited you to collaborate on the {{ $account->name }} account! <br>
        Content Launch is a tool to manage content marketing efforts and we think you'll benefit from using it too. <br>
        Check it out... It's free, it's easy and it has lots of cool features you can use.
    </p>
    <p>
        Click the link below (or copy and paste on your browser location bar) to collaborate with me:
    </p>
    <a href="{{ $link }}">
        <button>Accept invitation</button>
    </a>
@endsection
