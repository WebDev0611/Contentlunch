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
                    {{ Form::clLabel('Name') }}
                    <div class="col-sm-10">
                        {{ Form::clText('name') }}
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::clLabel('Account Type') }}
                    <div class="col-sm-10">
                        {{ Form::select('account_type_id', $accountTypes, old('account_type_id'), ['class' => 'form-control']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}

    <div class="ibox">
        <div class="ibox-title"><h5>New Subscription</h5></div>
        <div class="ibox-content">
            {{ Form::open([ 'url' => route('admin.account_subscriptions.store', $account) ]) }}

            {{ Form::hidden('valid', 1) }}

            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-2">
                        <p>
                            Adding a new subscription will deactivate any previous ones and new subscriptions won't be attached
                            to a Stripe client ID, so any subscription created this way will be free for the user.
                        </p>
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::clLabel('Subscription Type') }}
                    <div class="col-sm-10">
                        {{
                            Form::select(
                                'subscription_type',
                                $subscriptionTypes,
                                null,
                                [
                                    'class' => 'form-control',
                                    'placeholder' => 'Select a Subscription Plan'
                                ]
                            )
                        }}
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::clLabel('Starting Date') }}
                    <div class="col-sm-10">
                        <div class="input-group date">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>

                            <input name='start_date' type="text" class="form-control" value="{{ Carbon\Carbon::now()->format('m/d/Y') }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::clLabel('Expiration Date') }}
                    <div class="col-sm-10">
                        <div class="input-group date">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>

                            <input name="expiration_date" type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-save"></i>
                            Add Subscription
                        </button>
                    </div>
                </div>

                {{ Form::close() }}
            </div>
        </div>
        <div class="ibox-title"><h5>Current Subscriptions</h5></div>
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
@stop

@push('admin.scripts')
<script>

    $('.input-group.date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        format: 'mm/dd/yyyy',
    });

</script>
@endpush