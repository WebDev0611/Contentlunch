@php
    $staticData = ['title' => 'Prescriptions Report'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>
        <b>{{ $now->format('m/d/Y') }}</b>
    </p>
    <p>
        User Prescriptions between {{ $now->format('m/d/Y H:i:s') }} and {{ $yesterday->format('m/d/Y H:i:s') }}
    </p>
    <p>
        Total prescriptions: <strong>{{ count($prescriptions) }}</strong>
    </p>

    @if(count($prescriptions) > 0)
        <table>
            <thead>
            <tr>
                <th>User</th>
                <th>Content Prescription</th>
                <th>URL</th>
            </tr>
            </thead>
            <tbody>
            @foreach($prescriptions as $prescription)
                <tr>
                    <td>{{ \App\User::find($prescription->user_id)->email }}</td>
                    <td>{{ \App\ContentPrescription::find($prescription->content_prescription_id)->content_package }}</td>
                    <td>{{ $prescription->url }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

@endsection