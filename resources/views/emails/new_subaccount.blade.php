@php
    $staticData = ['title' => 'New sub-account added'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>
        Hey there! <br>
        We're noticing you about the new sub-account added to
        your Content Launch account.
        <br>
        New sub-account: <strong>{{ $accName }}</strong><br>
    </p>
@endsection