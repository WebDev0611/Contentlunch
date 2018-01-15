@php
    $staticData = ['title' => 'Welcome to Content Launch!'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>
        Thank you for signing up at {{ trans('messages.company') }}! <br>
        You can log into your private account:
    </p>
    <a href="{{ route('login') }}">
        <button>Click here to login</button>
    </a>
    <p>
        Email: {{ $user->email }} <br>
        Password: <strong>Your chosen password</strong>
    </p>
@endsection