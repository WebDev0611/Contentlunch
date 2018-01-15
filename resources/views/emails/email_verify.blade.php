@php
    $staticData = ['title' => 'Verify Email Address Change'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>
        You recently requested a user email change from your Content Launch account. <br>
        Please follow the link below to verify your new email address:
    </p>
    <p style="text-align: center; padding: 30px 0;">
        <a href="{{ URL::to('settings/email/verify/') }}" style="padding-left: 4px;padding-right: 4px;padding-top: 9px;padding-bottom: 9px;width: 100%;background: #2482ff;color: #ffffff;border-radius: 3px;font-family: 'Source Sans Pro', sans-serif;font-weight: bold;font-size: 22px;text-transform: uppercase;text-decoration: none;font-weight: normal;letter-spacing: .05em;transition: 0.2s;display: inline-block;margin-bottom: 0;text-align: center;vertical-align: middle;touch-action: manipulation;cursor: pointer;background-image: none;border: 1px solid transparent;white-space: nowrap;line-height: 1.428571429;user-select: none;">Verify address change</a>
    </p>
    <p>
        If you didn't make this request, please let us know immediately.
    </p>
@endsection