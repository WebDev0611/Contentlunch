@php
    $staticData = ['title' => 'You have Content Orders that are pending approval'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>
        This is an automatic email notification from the Content Launch app.<br><br>
        <b>You have Content Orders that are pending approval.</b>
    </p>
    <a href="{{route('content_orders.index', [], true)}}">
        <button>Click here to view your orders</button>
    </a>
@endsection
