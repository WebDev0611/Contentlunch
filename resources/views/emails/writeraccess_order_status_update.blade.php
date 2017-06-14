@php
    $staticData = ['title' => 'Content Order Status Change'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>
        This is an automatic email notification from the Content Launch app.<br><br>
        <b>Your order's status has changed:</b>
    </p>
    <table>
        <tr>
            <td><b>Old status:</b></td>
            <td>{{ $oldStatus }}</td>
        </tr>
        <tr>
            <td><b>New status:</b></td>
            <td>{{ $newStatus }}</td>
        </tr>
    </table>
    <a href="{{route('contentOrder', $orderId)}}">
        <button>Click here to view your order</button>
    </a>
@endsection
