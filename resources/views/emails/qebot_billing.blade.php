@php
    $staticData = ['title' => 'Qebot Billing Report'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')

    <h1> Qebot Billing for {{ $now->format('m/d/Y') }}</h1>
    <p><b>Qebot:</b> {{$amount}} * $5 = {{$charge}}</p>

@endsection