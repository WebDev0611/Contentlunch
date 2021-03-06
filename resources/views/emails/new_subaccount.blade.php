@php
    $staticData = ['title' => 'New sub-account added'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>Hey there!</p>
    <p>We're noticing you about the new sub-account added to your Content Launch account.</p>
    <p>New sub-account: <strong>{{ $accName or 'Undefined' }}</strong></p>
@endsection