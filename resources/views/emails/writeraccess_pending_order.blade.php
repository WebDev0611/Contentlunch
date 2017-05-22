@php
    $staticData = ['title' => 'You have Content Orders that are pending approval'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>
        This is an automatic email notification from Content Launch triggered by WriterAccess.<br><br>
        <b>You have Content Orders that are pending approval.</b>
    </p>
    <a href="{{route('content_orders.index')}}">
        <button>Click here to view your orders</button>
    </a>
@endsection
