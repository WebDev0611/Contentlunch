@php
    $staticData = ['title' => 'Order Failed'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>
        This is an automatic notification about failed order. <br />
        Order details below:
    </p>

    @php
        var_dump($order);
    @endphp
@endsection
