@php
    $staticData = ['title' => 'Notice: User wants to disable a sub-account'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>
        User <strong>{{ $userName }}</strong> selected an option to disable one of their sub-accounts.
        Since the selected sub-account was created before automatic cancelling was implemented,
        their sub-account and the Stripe subscription need to be cancelled manually. <br><br>
    <h3>Account data:</h3>
    <br>
    Account Name: <strong>{{ $accName }}</strong><br>
    Parent Account Name: <strong>{{ $parentAccName }}</strong><br>
    User Name: <strong>{{ $userName }}</strong><br>
    User Email: <strong>{{ $userEmail }}</strong><br>
    </p>
@endsection