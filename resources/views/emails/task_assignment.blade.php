@php
    $staticData = ['title' => 'Task assigned to you'];
@endphp

@extends('emails.layouts.master', $staticData)
@section('content')
    <p>You've recently been assigned a task:</p>

    <table>
        <tr>
            <td><b>Task:</b></td>
            <td><a href="{{ route('tasks.edit', $task->id) }}">{{ $task->name }}</a></td>
        </tr>
        <tr>
            <td><b>Due date:</b></td>
            <td>{{ $task->present()->dueDateFormat }}</td>
        </tr>
        <tr>
            <td><b>Description:</b></td>
            <td>{{ $task->explanation }}</td>
        </tr>
    </table>
@endsection
