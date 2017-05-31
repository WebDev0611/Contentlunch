@php
    $staticData = ['title' => 'New message on your order'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>
        This is an automatic email notification from Content Launch app.<br><br>
        <b>You have a new comment on your content order:</b>
    </p>
    <table>
        <tr>
            <td><b>Order title:</b></td>
            <td>{{ $data['order_title'] }}</td>
        </tr>
        <tr>
            <td><b>Sender:</b></td>
            <td>{{ $data['sender'] }}</td>
        </tr>
        <tr>
            <td><b>Message:</b></td>
            <td>{{ $data['message'] }}</td>
        </tr>
        <tr>
            <td><b>Timestamp:</b></td>
            <td>{{ $data['timestamp'] }}</td>
        </tr>
    </table>
    <a href="{{route('contentOrderComments', $data['order_id'])}}">
        <button>Click here to reply</button>
    </a>
@endsection
