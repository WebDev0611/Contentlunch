@php
    $staticData = ['title' => 'Subscription Plan Change'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>Hey there!</p>
    <p>We're noticing you about the recent subscription plan change on your Content Launch account.</p>
    <p>
        Old plan: {{ $oldPlanName or 'Undefined' }}<br>
        New plan: <b>{{ $newPlanName or 'Undefined' }}</b>
    </p>
@endsection