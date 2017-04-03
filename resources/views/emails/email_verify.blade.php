@php
    $staticData = ['title' => 'Verify Email Address Change'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
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
@endsection