@php
    $staticData = ['title' => 'User limit reached'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>
        Hey! An invited user, with the email {{ $email or 'Undefined' }}, just tried to join your team but couldn't because your account has reached its maximum users' limit.
    </p>
    <p>Upgrade now to increase your limits.</p>
    <p style="text-align: center; padding: 30px 0;">
        <a href="{{ route('subscription') }}" style="padding-left: 4px;padding-right: 4px;padding-top: 9px;padding-bottom: 9px;width: 100%;background: #2482ff;color: #ffffff;border-radius: 3px;font-family: 'Source Sans Pro', sans-serif;font-weight: bold;font-size: 22px;text-transform: uppercase;text-decoration: none;font-weight: normal;letter-spacing: .05em;transition: 0.2s;display: inline-block;margin-bottom: 0;text-align: center;vertical-align: middle;touch-action: manipulation;cursor: pointer;background-image: none;border: 1px solid transparent;white-space: nowrap;line-height: 1.428571429;user-select: none;">Upgrade now</a>
    </p>
@endsection