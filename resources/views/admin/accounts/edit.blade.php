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

    <div class="ibox">
        <div class="ibox-title"><h5>Account Details</h5></div>
        <div class="ibox-content">
            {{ Form::open([ 'url' => route('admin.accounts.update', $account), 'class' => 'form-horizontal' ]) }}
            <div class="form-group">
                {{ Form::clLabel('Name') }}
                <div class="col-sm-10">
                    {{ Form::clText('name') }}
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>

@stop