@php
    $staticData = ['title' => 'Subscription Plan Change'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>
        Hey there! <br>
        We're noticing you about the recent subscription plan change on
        your Content Launch account.
    </p>
    <p>
        Old plan: {{ $oldPlanName }}<br>
        New plan: <b>{{ $newPlanName }}</b>
    </p>
@endsection