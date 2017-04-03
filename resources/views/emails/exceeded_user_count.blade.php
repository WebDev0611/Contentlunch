@php
    $staticData = ['title' => 'User limit reached'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>
        Hey! An invited user, with the email {{ $email }}, just tried to join your team but couldn't because
        your account has reached its maximum users' limit.
    </p>
    <a href="{{ route('subscription') }}">
        <button>Upgrade now to increase your limits.</button>
    </a>
@endsection