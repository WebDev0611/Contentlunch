@php
    $staticData = ['title' => 'Content Order - API error occurred'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>
        This is an automatic email notification from the Content Launch app triggered by error when trying to place the order.<br><br>
        <b>Order details:</b>
    </p>
    <table>
        <tr>
            <td><b>Partial Order ID:</b></td>
            <td>{{ $data['partial_order_id'] }}</td>
        </tr>
        <tr>
            <td><b>User name:</b></td>
            <td>{{ $data['user_name'] }}</td>
        </tr>
        <tr>
            <td><b>User email:</b></td>
            <td>{{ $data['user_email'] }}</td>
        </tr>
        <tr>
            <td><b>Account ID:</b></td>
            <td>{{ $data['acc_id'] }}</td>
        </tr>
        <tr>
            <td><b>Timestamp:</b></td>
            <td>{{ date('M-d-Y H:i:s') }}</td>
        </tr>
        <tr>
            <td><b>Response:</b></td>
            <td>{{ $data['api_response'] }}</td>
        </tr>
    </table>
@endsection
