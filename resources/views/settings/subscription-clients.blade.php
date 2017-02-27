@extends('layouts.master')

@section('scripts.head')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@stop

@section('content')
    <div class="workspace">
        <div class="panel clearfix">

            @include('settings.partials.subscription_sidebar')

            <div class="panel-main left-separator">

                <div class="panel-header">
                    <!-- navigation -->
                    @include('settings.partials.subscription_navigation')
                </div>

                <div class="panel-container col-md-8">
                    @if ($errors->any())
                        <div class="alert alert-danger" id="formError">
                            <p><strong>Oops! We had some errors:</strong>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            </p>
                        </div>
                    @endif
                </div>

                <div class="row">

                    <div class="col-md-10 col-md-offset-1">

                        <div class="row">

                            <div class="col-md-10">

                                <div class="dashboard-content-box height-double">
                                    <table class="table table-list">
                                        <thead>
                                        <tr>
                                            <th>Client</th>
                                            <th>Status</th>
                                            <th>Expiration date</th>
                                            <th>Options</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($accounts as $account)
                                            <tr>
                                                <td>
                                                    <div class="clientlogo">
                                                        <img src="/images/logo-client-fake.jpg" alt="XX"/>
                                                    </div>
                                                    <p class="title">{{ $account->name }}</p>
                                                </td>
                                                <td>{{ $account->contents->count() }}</td>
                                                <td>{{ $account->users->count() }}</td>
                                                <td>
                                                    <button class="btn btn-primary">Renew</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

@stop

@section('scripts')
    <script>
        @if(isset($activeSubscription))
            {!! 'var subscriptionTypeSlug="' . $activeSubscription->subscriptionType->slug . '";' !!}
        @endif
    </script>
    <script src="/js/subscriptions.js"></script>
@stop