@php
    $staticData = ['title' => 'Queued Job Failed'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>
        This is an automatic notification about failed queued job in your app. <br>
        Job details below:
    </p>

    <table>
        <tr>
            <td><b>Connection name:</b></td>
            <td>{{$data['connectionName']}}</td>
        </tr>
        <tr>
            <td><b>Due date:</b></td>
            <td>{{json_encode($data['job'])}}</td>
        </tr>
        <tr>
            <td><b>Description:</b></td>
            <td>{{json_encode($data['data'])}}</td>
        </tr>
    </table>
@endsection
