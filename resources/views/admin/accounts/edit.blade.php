@extends('admin.layouts.master')

@section('header')
    @include('admin.partials.header', [
        'title' => "Editing $account->name",
        'breadcrumbs' => [
            [ 'name' => 'Accounts', 'url' => route('admin.accounts.index') ],
            [ 'name' => $account->name, 'url' => route('admin.accounts.show', $account) ],
            [ 'name' => "Editing $account->name", 'url' => route('admin.accounts.edit', $account) ],
        ]
    ])
@stop

@section('content')
    @include('admin.accounts.partials.actions')


@stop