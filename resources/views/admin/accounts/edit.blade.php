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
    {{ Form::model($account, [ 'url' => route('admin.accounts.update', $account) ]) }}
    @include('admin.accounts.partials.actions')

    <div class="ibox">
        <div class="ibox-title"><h5>Account Details</h5></div>
        <div class="ibox-content">
            <div class="form-horizontal">
                <div class="form-group">
                    {{ Form::clLabel('Name', 4) }}
                    <div class="col-sm-8">
                        {{ Form::clText('name') }}
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::clLabel('Account Type', 4) }}
                    <div class="col-sm-8">
                        {{ Form::select('account_type_id', $accountTypes, old('account_type_id'), ['class' => 'form-control']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ibox">
        <div class="ibox-title"><h5>Subscriptions</h5></div>
        <div class="ibox-content">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2" class="col-sm-1"></th>
                        <th>Type</th>
                        <th>Start Date</th>
                        <th>Expiration Date</th>
                        <th>Price</th>
                        <th>Created</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($subscriptions as $subscription)
                    <tr>
                        <td>
                            @if ($subscription->stripe_subscription_id)
                                <span class="label label-info">Stripe Subscription</span>
                            @else
                                <span class="label label-plan">No Stripe Subscription</span>
                            @endif
                        </td>
                        <td>
                            @if ($subscription->valid == 1)
                                <span class="label label-primary">Active</span>
                            @else
                                <span class="label label-plain">Inactive</span>
                            @endif
                        </td>
                        <td>
                            {{ $subscription->subscriptionType->name }}
                        </td>
                        <td>{{ $subscription->present()->startDateFormat }}</td>
                        <td>{{ $subscription->present()->expirationDateFormat }}</td>
                        <td>{{ $subscription->present()->price }}</td>
                        <td>{{ $subscription->present()->createdAtFormat('m/d/Y H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <pre>{!! print_r($subscriptions->toArray(), true) !!}</pre>

    {{ Form::close() }}

@stop