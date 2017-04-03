@php
    $staticData = ['title' => 'User Report'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>
        <b>{{ $now->format('m/d/Y') }}</b>
    </p>
    <p>
        The following users registered between {{ $now->format('m/d/Y H:i:s') }} and {{ $yesterday->format('m/d/Y H:i:s') }}:
    </p>
    <p>
        Total users registered: <strong>{{ $users->count() }}</strong>
    </p>

    @if ($users->count())
        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Location</th>
                <th>Account(s)</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->present()->location }}</td>
                    <td>{{ $user->present()->accountList }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>No users were registered on the specified period.</p>
    @endif

@endsection