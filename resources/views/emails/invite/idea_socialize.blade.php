@php
    $staticData = ['title' => 'Content Launch Idea'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>Hey!</p>
    <p>
        {{ $user->name }} has shared the "<strong>{{$idea->name}}</strong>" idea with you! <br>
    </p>
    <p>
        Click the link below (or copy and paste on your browser location bar) to view this idea:
    </p>
    <a href="{{ $link }}">
        <button>View Idea</button>
    </a>
@endsection
