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

    <div class="row">
        <div class="col-md-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title"><h5>Account Details</h5></div>
                <div class="ibox-content">
                    <form action="" class="form-horizontal">
                        <div class="form-group">
                            {{ Form::clLabel('Name', 4) }}
                            <div class="col-sm-8">
                                <div class="form-control-static">
                                    {{ $account->name }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::clLabel('Account Type', 4) }}
                            <div class="col-sm-8">
                                <div class="form-control-static">
                                    {{ $account->present()->accountType }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::clLabel('Sub-Accounts', 4) }}
                            <div class="col-sm-8">
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
        </div>

        <div class="col-md-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title"><h5>Subscription Details</h5></div>
                <div class="ibox-content">
                    <div class="form-horizontal">
                        <div class="form-group">
                            {{ Form::clLabel('Subscription Type', 4) }}
                            <div class="col-sm-8">
                                <div class="form-control-static">
                                    {{ $account->activeSubscription()->present()->type() }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::clLabel('Expiration Date', 4) }}
                            <div class="col-sm-8">
                                <div class="form-control-static">
                                    {{ $account->activeSubscription()->present()->expirationDateFormat() }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::clLabel('Subscription Payments', 4) }}
                            <div class="col-sm-8">
                                <div class="form-control-static">
                                    {{ $account->activeSubscription()->present()->price() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ibox float-e-margins m-t">
        <div class="ibox-title"><h5>Users</h5></div>
        <div class="ibox-content">
            @include('admin.users.partials.list', compact('users'))
        </div>
    </div>

@stop