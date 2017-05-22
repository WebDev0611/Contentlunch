@php
    $staticData = ['title' => 'Content Launch Invite'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>Hey!</p>
    <p>
        {{ $user->name }} has invited you to collaborate on the {{ $account->name }} account! <br>
    </p>
    <p>
        Click the link below (or copy and paste on your browser location bar) to collaborate with me:
    </p>
    <a href="{{ $link }}">
        <button>Accept invitation</button>
    </a>
@endsection
