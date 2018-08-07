@php
    $staticData = ['title' => 'Sub-account disabled'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>
        Hey there! <br>
        One of the sub-accounts in your Content Launch account has been disabled.</p>
    <p>
        If you think this was a mistake, please contact the Content Launch Support.
    </p>
    <p>
        Disabled sub-account: <strong>{{ $accName or 'Undefined' }}</strong>
    </p>
@endsection