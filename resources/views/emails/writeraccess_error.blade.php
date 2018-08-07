@php
    $staticData = ['title' => 'Content Order - API error occurred'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>This is an automatic email notification from the Content Launch app triggered by error when trying to place the order.</p>
    
    <p><strong>Order details:</strong></p>

    <table>
        <tr>
            <td><strong>Partial Order ID:</strong></td>
            <td>{{ $data['partial_order_id'] }}</td>
        </tr>
        <tr>
            <td><strong>User name:</strong></td>
            <td>{{ $data['user_name'] }}</td>
        </tr>
        <tr>
            <td><strong>User email:</strong></td>
            <td>{{ $data['user_email'] }}</td>
        </tr>
        <tr>
            <td><strong>Account ID:</strong></td>
            <td>{{ $data['acc_id'] }}</td>
        </tr>
        <tr>
            <td><strong>Timestamp:</strong></td>
            <td>{{ date('M-d-Y H:i:s') }}</td>
        </tr>
        <tr>
            <td><strong>Response:</strong></td>
            <td>{{ $data['api_response'] }}</td>
        </tr>
    </table>
@endsection
