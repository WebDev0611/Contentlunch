@php
    $staticData = ['title' => 'New Message from WriterAccess'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>
        This is an automatic email notification from Content Launch app triggered by WriterAccess.<br><br>
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
    <p>To post an answer, please reply to this email.</p>
@endsection
