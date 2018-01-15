@php
    $staticData = ['title' => 'Content Launch Invite'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>Hey!</p>

    <p><strong>{{ $user->name or 'Undefined' }}</strong> has invited you to collaborate on the <strong>{{ $account->name or 'Undefined' }}</strong> account!</p>

    <p>Click the link below (or copy and paste on your browser location bar) to collaborate with me:</p>
   
    <p style="text-align: center; padding: 30px 0;">
        <a href="{{ $link or '#' }}" style="padding-left: 4px;padding-right: 4px;padding-top: 9px;padding-bottom: 9px;width: 100%;background: #2482ff;color: #ffffff;border-radius: 3px;font-family: 'Source Sans Pro', sans-serif;font-weight: bold;font-size: 22px;text-transform: uppercase;text-decoration: none;font-weight: normal;letter-spacing: .05em;transition: 0.2s;display: inline-block;margin-bottom: 0;text-align: center;vertical-align: middle;touch-action: manipulation;cursor: pointer;background-image: none;border: 1px solid transparent;white-space: nowrap;line-height: 1.428571429;user-select: none;">Accept invitation</a>
    </p>
@endsection
