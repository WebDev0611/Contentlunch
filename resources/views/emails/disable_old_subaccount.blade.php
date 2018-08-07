@php
    $staticData = ['title' => 'Notice: User wants to disable a sub-account'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>
        User <strong>{{ $userName or 'Undefined' }}</strong> selected an option to disable one of their sub-accounts.
        Since the selected sub-account was created before automatic cancelling was implemented,
        their sub-account and the Stripe subscription need to be cancelled manually.
    </p>
    <h3>Account data:</h3>
    <p>
        Account Name: <strong>{{ $accName or 'Undefined' }}</strong><br />
        Parent Account Name: <strong>{{ $parentAccName or 'Undefined' }}</strong><br />
        User Name: <strong>{{ $userName or 'Undefined' }}</strong><br />
        User Email: <strong>{{ $userEmail or 'Undefined' }}</strong>
    </p>
@endsection