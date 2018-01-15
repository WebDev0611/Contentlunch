@php
    $staticData = ['title' => 'REVIEW DOCUMENT', 'image' => 'review-document.png'];
@endphp

@extends('emails.layouts.master', $staticData)

@section('content')
    <p style="font-size: 16px; line-height: 1.5em; margin: 0; margin-bottom: 14px;"><strong>Jenny Hurley</strong> shared a <strong>Volkswagen Golf GTI/R review</strong> document with you. She would like to get your feedback on it. Please click the link below to go to online feedback app.</p>

    <p style="font-size: 16px; line-height: 1.5em; margin: 0; margin-bottom: 14px;">You will be able to read, review and submit your content feedback and approve it or send it for additional edit. Once you do any of these actions Jenny will be automatically notified and your comments will be visible.</p>

    <p style="text-align: center; padding: 30px 0;">
        <a href="{{ URL::to('/') }}" style="padding-left: 4px;padding-right: 4px;padding-top: 9px;padding-bottom: 9px;width: 100%;background: #2482ff;color: #ffffff;border-radius: 3px;font-family: 'Source Sans Pro', sans-serif;font-weight: bold;font-size: 22px;text-transform: uppercase;text-decoration: none;font-weight: normal;letter-spacing: .05em;transition: 0.2s;display: inline-block;margin-bottom: 0;text-align: center;vertical-align: middle;touch-action: manipulation;cursor: pointer;background-image: none;border: 1px solid transparent;white-space: nowrap;line-height: 1.428571429;user-select: none;">Review document</a>
    </p>
@endsection