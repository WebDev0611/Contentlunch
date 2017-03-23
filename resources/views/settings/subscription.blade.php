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

                <div class="col-md-10 col-md-offset-1">

                    <div class="row ">
                        <div class="col-md-10 ">
                            <div class="col-md-8 col-md-offset-2 text-center">
                                <h3>Get a Content Launch Subscription!</h3>
                                <p>Thanks for choosing Content Launch. With a paid account, you will enjoy dozens of great features.
                                To begin your subscription, please complete the transaction below. Thanks!
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-10 ">
                            <div class="col-md-6 col-md-offset-3 text-center">
                                <div class="billing-buttons">
                                    <button type="button" class="btn btn-default monthly selected">Monthly Billing</button>
                                    <button type="button" class="btn btn-default annually">Annual Billing</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! Form::open([ 'id'=>'subscriptionForm', 'route' => 'subscription' ]) !!}

                    <div class="row">

                            <div class="col-md-10 no-padding @if($account->isAgencyAccount()) is-account @endif">
                                <div class="plan-selector plan-free plan-trial col-md-4">

                                    <div class="panel panel-default">

                                        <div class="panel-body">

                                            @if ($activeSubscription->subscriptionType->slug == 'free' || $activeSubscription->subscriptionType->slug == 'trial')
                                                <span class="label current-plan">CURRENT PLAN</span>
                                            @endif

                                            <div class="tab-pane fade in active">

                                                <h3 class="plan-title">Standard</h3>
                                                <hr/>
                                                <div class="amount">
                                                    <span>FREE</span>
                                                </div>
                                                <p class="amount-info"></p>

                                                @if($account->isAgencyAccount())
                                                    <div class="margin-agency-free"></div>
                                                @endif

                                                @php
                                                $freePlan = App\SubscriptionType::whereSlug('free')->first();
                                                @endphp
                                                <ul class="description">
                                                    <li>Up to <strong>{{ $freePlan->limit('campaigns') }}</strong> campaigns/mo</li>
                                                    <li>Up to <strong>{{ $freePlan->limit('content_launch') }}</strong> content launches/mo</li>
                                                    <li>Up to <strong>{{ $freePlan->limit('topic_search') }}</strong> topic searches/mo
                                                    <li>Up to <strong>{{ $freePlan->limit('calendars') }}</strong> calendar
                                                    </li>
                                                    <li><h4>Up to <strong>{{ $freePlan->limit('users_per_account') }}</strong> users</h4></li>
                                                </ul>

                                                <label for="plan-0" class="checkbox-tag plan">
                                                    <input id="plan-0" type="checkbox" plan-name="free"
                                                           plan-type=""
                                                           plan-price="{{$planPrices['free']}}"
                                                           plan-slug="free">
                                                    <span>Select</span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <div class="plan-selector plan-basic-monthly col-md-4">

                                    <div class="panel panel-default">

                                        <div class="panel-body">

                                            @if ($activeSubscription->subscriptionType->slug == 'basic-monthly')
                                                <span class="label current-plan">CURRENT PLAN</span>
                                            @endif

                                            <div class="tab-pane fade in active">

                                                <h3 class="plan-title">Basic</h3>
                                                <hr/>
                                                <div class="amount">
                                                    <span>${{ number_format($planPrices['basic-monthly']) }}</span>
                                                </div>
                                                <p class="amount-info">monthly</p>

                                                @if($account->isAgencyAccount())
                                                    <h4 class="text-no-margin">+</h4>
                                                    <div class="amount-small">
                                                        <span>${{ number_format($planClientPrices['basic-monthly']) }}</span>
                                                    </div>
                                                    <p class="amount-info-small">per agency client/mo</p>
                                                @endif

                                                <ul class="description">
                                                    <li><strong>Unlimited</strong> campaigns</li>
                                                    <li><strong>Unlimited</strong> content launches</li>
                                                    <li><strong>Unlimited</strong> tasks, ideas, and calendars
                                                    </li>
                                                    <li><h4>Up to <strong>{{ App\SubscriptionType::whereSlug('basic-monthly')->first()->limit('users_per_account') }}</strong> users</h4></li>
                                                </ul>

                                                <label for="plan-1" class="checkbox-tag plan">
                                                    <input id="plan-1" type="checkbox" plan-name="basic"
                                                           plan-type="month"
                                                           plan-price="{{$planPrices['basic-monthly']}}"
                                                           plan-slug="basic-monthly">
                                                    <span>Select</span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="plan-selector plan-basic-annually col-md-4" style="display: none;">

                                    <div class="panel panel-default">

                                        <div class="panel-body">

                                            @if ($activeSubscription->subscriptionType->slug == 'basic-annually')
                                                <span class="label current-plan">CURRENT PLAN</span>
                                            @endif

                                            <div class="tab-pane fade in active">

                                                <h3 class="plan-title">Basic</h3>
                                                <hr/>
                                                <div class="amount">
                                                    <span>${{ number_format($planPrices['basic-annually']) }}</span>
                                                </div>
                                                <p class="amount-info">annually</p>

                                                @if($account->isAgencyAccount())
                                                    <h4 class="text-no-margin">+</h4>
                                                    <div class="amount-small">
                                                        <span>${{ number_format($planClientPrices['basic-annually']) }}</span>
                                                    </div>
                                                    <p class="amount-info-small">per agency client/mo</p>
                                                @endif

                                                <ul class="description">
                                                    <li><strong>Unlimited</strong> campaigns</li>
                                                    <li><strong>Unlimited</strong> content launches</li>
                                                    <li><strong>Unlimited</strong> tasks, ideas, and calendars
                                                    </li>
                                                    <li><h4>Up to <strong>{{ App\SubscriptionType::whereSlug('basic-annually')->first()->limit('users_per_account') }}</strong> users</h4></li>
                                                </ul>

                                                <label for="plan-2" class="checkbox-tag plan">
                                                    <input id="plan-2" type="checkbox" plan-name="basic"
                                                           plan-type="year"
                                                           plan-price="{{$planPrices['basic-annually']}}"
                                                           plan-slug="basic-annually">
                                                    <span>Select</span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <div class="plan-selector plan-pro-monthly col-md-4">

                                    <div class="panel panel-default">

                                        <div class="panel-body">

                                            @if ($activeSubscription->subscriptionType->slug == 'pro-monthly')
                                                <span class="label current-plan">CURRENT PLAN</span>
                                            @endif

                                            <div class="tab-pane fade in active">

                                                <h3 class="plan-title">Pro</h3>
                                                <hr/>
                                                <div class="amount">
                                                    <span>${{ number_format($planPrices['pro-monthly']) }}</span>
                                                </div>
                                                <p class="amount-info">monthly</p>

                                                @if($account->isAgencyAccount())
                                                    <h4 class="text-no-margin">+</h4>
                                                    <div class="amount-small">
                                                        <span>${{ number_format($planClientPrices['pro-monthly']) }}</span>
                                                    </div>
                                                    <p class="amount-info-small">per agency client/mo</p>
                                                @endif

                                                <ul class="description">
                                                    <li><strong>Unlimited</strong> campaigns</li>
                                                    <li><strong>Unlimited</strong> content launches</li>
                                                    <li><strong>Unlimited</strong> tasks, ideas, and calendars
                                                    </li>
                                                    <li><h4>Up to <strong>{{ App\SubscriptionType::whereSlug('pro-monthly')->first()->limit('users_per_account') }}</strong> users</h4></li>
                                                </ul>

                                                <label for="plan-3" class="checkbox-tag plan">
                                                    <input id="plan-3" type="checkbox" plan-name="pro"
                                                           plan-type="month"
                                                           plan-price="{{$planPrices['pro-monthly']}}"
                                                           plan-slug="pro-monthly">
                                                    <span>Select</span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="plan-selector plan-pro-annually col-md-4" style="display: none;">

                                    <div class="panel panel-default">

                                        <div class="panel-body">

                                            @if ($activeSubscription->subscriptionType->slug == 'pro-annually')
                                                <span class="label current-plan">CURRENT PLAN</span>
                                            @endif

                                            <div class="tab-pane fade in active">

                                                <h3 class="plan-title">Pro</h3>
                                                <hr/>
                                                <div class="amount">
                                                    <span>${{ number_format($planPrices['pro-annually']) }}</span>
                                                </div>
                                                <p class="amount-info">annually</p>

                                                @if($account->isAgencyAccount())
                                                    <h4 class="text-no-margin">+</h4>
                                                    <div class="amount-small">
                                                        <span>${{ number_format($planClientPrices['pro-annually']) }}</span>
                                                    </div>
                                                    <p class="amount-info-small">per agency client/mo</p>
                                                @endif

                                                <ul class="description">
                                                    <li><strong>Unlimited</strong> campaigns</li>
                                                    <li><strong>Unlimited</strong> content launches</li>
                                                    <li><strong>Unlimited</strong> tasks, ideas, and calendars
                                                    </li>
                                                    <li><h4>Up to <strong>{{ App\SubscriptionType::whereSlug('pro-annually')->first()->limit('users_per_account') }}</strong> users</h4></li>
                                                </ul>

                                                <label for="plan-4" class="checkbox-tag plan">
                                                    <input id="plan-4" type="checkbox" plan-name="pro"
                                                           plan-type="year"
                                                           plan-price="{{$planPrices['pro-annually']}}"
                                                           plan-slug="pro-annually">
                                                    <span>Select</span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-10  stripe-container" @if(!empty($user->stripe_customer_id)) style="display:none" @endif>

                                <div class="row">
                                    <div class="col-md-12">

                                        <div
                                                id="paymentErrors"
                                                class='alert alert-danger alert-forms'
                                                style='display:none'></div>

                                        <h4 class="purchase-title">Make deposit via Stripe</h4>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-form-group">
                                                    <label>CARD NUMBER</label>
                                                    <input type="text" data-stripe='number' class='input'
                                                           placeholder='Card Number'>
                                                </div>
                                                <div class="input-form-group">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <label>Expiration month</label>
                                                            <input type="text" data-stripe='exp-month' placeholder='MM'
                                                                   class='input'>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Expiration year</label>
                                                            <input type="text" data-stripe='exp-year' placeholder='YYYY'
                                                                   class='input'>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="#">CVC</label>
                                                            <input type="text" class="input" data-stripe='cvc'>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <img src="/images/stripe.png" alt=""
                                                     class="img-responsive center-block">
                                            </div>

                                            @if(!empty($user->stripe_customer_id))
                                                <input type="hidden" name="stripe-customer-id"
                                                       value="{{$user->stripe_customer_id}}">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-10">
                                <div class="input-form-group">
                                    <div class="row">
                                        <div class="col-md-6 @if(isset($userCard)) col-md-offset-3  @endif">
                                            <label for="auto_renew" class="checkbox-tag">
                                                <input id="auto_renew" type="checkbox" name="auto_renew"
                                                       value="1">
                                                <span>Auto Renew</span>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                    <div class="row">
                            <div class="col-md-10">
                                <input
                                        type="submit"
                                        id="submit-btn"
                                        class='button button-primary button-extend text-uppercase'
                                        value='Submit Payment'>
                            </div>

                            <div class="col-md-10">
                                <div class="input-form-group loading" style='display:none'>
                                    <img src="/images/ring.gif" class='loading-relative' alt="">
                                </div>
                            </div>

                        </div>

                    {!! Form::close() !!}

                </div>

            </div>

        </div>
    </div>

@stop

@section('scripts')
    <script>
    (function () {
        Stripe.setPublishableKey("{{ getenv('STRIPE_PUBLISHABLE_KEY') }}");
    })();
    @if(isset($activeSubscription))
        {!! 'var subscriptionTypeSlug="' . $activeSubscription->subscriptionType->slug . '";' !!}
    @endif
    </script>
    <script src="/js/subscriptions.js"></script>

    <script>
        $(function(){
            //tasks
            $('#add-task-button').click(function() {
                add_task(addTaskCallback);
            });

            function addTaskCallback(task) {
                $('#addTaskModal').modal('hide');
            }
        });
    </script>
@stop