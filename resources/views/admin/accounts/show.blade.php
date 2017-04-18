@extends('admin.layouts.master')

@section('header')
    @include('admin.partials.header', [
        'title' => $account->name,
        'breadcrumbs' => [
            [ 'name' => 'Accounts', 'url' => route('admin.accounts.index') ],
            [ 'name' => $account->name, 'url' => route('admin.accounts.show', $account) ],
        ]
    ])
@stop

@section('content')
    @include('admin.accounts.partials.actions')

    <div class="ibox float-e-margins">
        <div class="ibox-title"><h5>Account Details</h5></div>
        <div class="ibox-content">
            <form action="" class="form-horizontal">
                <div class="form-group">
                    {{ Form::clLabel('Name') }}
                    <div class="col-sm-10">
                        <div class="form-control-static">
                            {{ $account->name }}
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::clLabel('Account Type') }}
                    <div class="col-sm-10">
                        <div class="form-control-static">
                            {{ $account->present()->accountType }}
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::clLabel('Sub-Accounts') }}
                    <div class="col-sm-10">
                        <div class="form-control-static">
                            @foreach ($account->childAccounts as $account)
                                @include('admin.accounts.partials.account_tag', compact('account'))
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop