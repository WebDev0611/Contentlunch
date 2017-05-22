@extends('layouts.master')

@section('scripts.head')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@stop

@section('content')
    <div class="workspace">
        <div class="panel clearfix">

            @include('settings.partials.sidebar')

            <div class="panel-main left-separator">

                <div class="panel-header">
                    <!-- navigation -->
                    @include('settings.partials.navigation')
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
                                            <th>Start date</th>
                                            <th>Expiration date</th>
                                            <th>Paid</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($accounts as $key => $account)
                                            <tr>

                                                <td>
                                                    <div class="clientlogo">
                                                        <img src="{{ $account->present()->account_image }}"
                                                             alt="{{ $account->name }}"/>
                                                    </div>
                                                    <p class="title">{{ $account->name }}</p>
                                                </td>

                                                @if($account->activeChildSubscriptions()->isEmpty())
                                                    {{-- Old sub-account (before implementing client subscriptions) --}}

                                                    <td>{{ date_format(date_create($account->parentAccount->activeSubscriptions()->first()->start_date), "n-j-y") }}</td>

                                                    @if($account->parentAccount->activeSubscriptions()->first()->expiration_date == '0000-00-00')
                                                        <td>-</td>
                                                    @else
                                                        <td>{{ date_format(date_create($account->parentAccount->activeSubscriptions()->first()->expiration_date), "n-j-y") }}</td>
                                                    @endif

                                                    @if($key < App\SubscriptionType::findBySlug('free')->limit('subaccounts_per_account'))
                                                        <td>$0/mo</td>
                                                    @else
                                                        <td>
                                                            ${{ number_format($account->parentAccount->activeSubscriptions()->first()->subscriptionType->price_per_client) }}
                                                            /mo
                                                        </td>
                                                    @endif

                                                @else
                                                    {{-- New sub-account --}}

                                                    <td>{{ date_format(date_create($account->activeChildSubscriptions()->first()->start_date), "n-j-y") }}</td>

                                                    @if($account->activeChildSubscriptions()->first()->expiration_date == '0000-00-00')
                                                        <td>-</td>
                                                    @else
                                                        <td>{{ date_format(date_create($account->activeChildSubscriptions()->first()->expiration_date), "n-j-y") }}</td>
                                                    @endif

                                                    <td>
                                                        ${{ number_format($account->activeChildSubscriptions()->first()->subscriptionType->price_per_client) }}
                                                        /mo
                                                    </td>

                                                @endif

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
    <script src="/js/subscriptions.js"></script>
    <script>
        $(function () {
            //tasks
            $('#add-task-button').click(function () {
                add_task(addTaskCallback);
            });

            function addTaskCallback(task) {
                $('#addTaskModal').modal('hide');
            }
        });
    </script>
@stop