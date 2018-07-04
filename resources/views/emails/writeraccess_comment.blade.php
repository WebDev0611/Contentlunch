@php
    $staticData = ['title' => 'New message on your order'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>This is an automatic email notification from Content Launch app.</p>

    <p><strong>You have a new comment on your content order:</strong></p>

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

    <p style="text-align: center; padding: 30px 0;">
        <a href="{{ route('contentOrder', $data['order_id']) }}" style="padding-left: 4px;padding-right: 4px;padding-top: 9px;padding-bottom: 9px;width: 100%;background: #2482ff;color: #ffffff;border-radius: 3px;font-family: 'Source Sans Pro', sans-serif;font-weight: bold;font-size: 22px;text-transform: uppercase;text-decoration: none;font-weight: normal;letter-spacing: .05em;transition: 0.2s;display: inline-block;margin-bottom: 0;text-align: center;vertical-align: middle;touch-action: manipulation;cursor: pointer;background-image: none;border: 1px solid transparent;white-space: nowrap;line-height: 1.428571429;user-select: none;">Click here to reply</a>
    </p>

@endsection