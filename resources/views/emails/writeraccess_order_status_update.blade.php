@php
    $staticData = ['title' => 'Content Order Status Change'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>This is an automatic email notification from the Content Launch app.</p>

    <p><strong>Your order's status has changed:</strong></p>

    <table>
        <tr>
            <td><b>Old status:</b></td>
            <td>{{ $oldStatus or 'Undefined' }}</td>
        </tr>
        <tr>
            <td><b>New status:</b></td>
            <td>{{ $newStatus or 'Undefined' }}</td>
        </tr>
    </table>

    <p style="text-align: center; padding: 30px 0;">
        <a href="{{route('contentOrder', $orderId)}}" style="padding-left: 4px;padding-right: 4px;padding-top: 9px;padding-bottom: 9px;width: 100%;background: #2482ff;color: #ffffff;border-radius: 3px;font-family: 'Source Sans Pro', sans-serif;font-weight: bold;font-size: 22px;text-transform: uppercase;text-decoration: none;font-weight: normal;letter-spacing: .05em;transition: 0.2s;display: inline-block;margin-bottom: 0;text-align: center;vertical-align: middle;touch-action: manipulation;cursor: pointer;background-image: none;border: 1px solid transparent;white-space: nowrap;line-height: 1.428571429;user-select: none;">View your order</a>
    </p>

@endsection
