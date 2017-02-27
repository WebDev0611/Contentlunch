@extends('layouts.master')

@section('scripts.head')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@stop

@section('content')
    <div class="workspace">
        <div class="panel clearfix">

            @include('settings.partials.subscription_sidebar')

            <div class="panel-main left-separator">

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

                                <div class="col-md-6">

                                    <div class="plan-selector plan-basic">
                                        <h2>Basic
                                        <span class="label label-primary current-plan">CURRENT PLAN</span>
                                        </h2>

                                        <div class="panel with-nav-tabs panel-default">
                                            <div class="panel-heading">
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a href="#tab-basic-month" data-toggle="tab"><h5>
                                                                Monthly</h5></a></li>
                                                    <li class="highlight"><a href="#tab-basic-year" data-toggle="tab">
                                                            <h5>Annually</h5>
                                                        </a> <span class="label label-success">Best Value -10%</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="panel-body">
                                                <div class="tab-content">
                                                    <div class="tab-pane fade in active" id="tab-basic-month">

                                                        <ul class="description">
                                                            <li><strong>Unlimited</strong> campaigns</li>
                                                            <li><strong>Unlimited</strong> content launches</li>
                                                            <li><strong>Unlimited</strong> tasks, ideas, and calendars
                                                            </li>
                                                            <li><h4>Up to <strong>5</strong> users</h4></li>
                                                        </ul>
                                                        <div class="amount">
                                                            <span class="dollar">$</span>
                                                            <span class="number">{{ number_format($planPrices['basic-monthly']) }}</span>
                                                        </div>
                                                        <p class="amount-info">Paid monthly</p>

                                                        <label for="plan-1" class="checkbox-tag plan">
                                                            <input id="plan-1" type="checkbox" plan-name="basic"
                                                                   plan-type="month"
                                                                   plan-price="{{$planPrices['basic-monthly']}}"
                                                                   plan-slug="basic-monthly">
                                                            <span>Sign me up!</span>
                                                        </label>
                                                    </div>
                                                    <div class="tab-pane fade" id="tab-basic-year">

                                                        <ul class="description">
                                                            <li class="bestvalue"><h4>You save <strong>10%</strong></h4>
                                                            </li>

                                                            <li><strong>Unlimited</strong> campaigns</li>
                                                            <li><strong>Unlimited</strong> content launches</li>
                                                            <li><strong>Unlimited</strong> tasks, ideas, and calendars
                                                            </li>
                                                            <li><h4>Up to <strong>5</strong> users</h4></li>
                                                        </ul>

                                                        <div class="amount">
                                                            <span class="dollar">$</span>
                                                            <span class="number">{{ number_format($planPrices['basic-annually']) }}</span>
                                                        </div>
                                                        <p class="amount-info">Paid annually</p>

                                                        <label for="plan-2" class="checkbox-tag plan">
                                                            <input id="plan-2" type="checkbox" plan-name="basic"
                                                                   plan-type="year"
                                                                   plan-price="{{$planPrices['basic-annually']}}"
                                                                   plan-slug="basic-annually">
                                                            <span>Sign me up!</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">

                                    <div class="plan-selector plan-pro">
                                        <h2>Pro</h2>

                                        <div class="panel with-nav-tabs panel-default">
                                            <div class="panel-heading">
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a href="#tab-pro-month" data-toggle="tab"><h5>
                                                                Monthly</h5></a></li>
                                                    <li><a href="#tab-pro-year" data-toggle="tab"><h5>Annually</h5></a>
                                                        <span class="label label-success">Best Value -10%</span></li>
                                                </ul>
                                            </div>
                                            <div class="panel-body">
                                                <div class="tab-content">
                                                    <div class="tab-pane fade in active" id="tab-pro-month">

                                                        <ul class="description">
                                                            <li><strong>Unlimited</strong> campaigns</li>
                                                            <li><strong>Unlimited</strong> content launches</li>
                                                            <li><strong>Unlimited</strong> tasks, ideas, and calendars
                                                            </li>
                                                            <li><h4>Up to <strong>10</strong> users</h4></li>
                                                        </ul>

                                                        <div class="amount">
                                                            <span class="dollar">$</span>
                                                            <span class="number">{{ number_format($planPrices['pro-monthly']) }}</span>
                                                        </div>
                                                        <p class="amount-info">Paid monthly</p>

                                                        <label for="plan-3" class="checkbox-tag plan">
                                                            <input id="plan-3" type="checkbox" plan-name="pro"
                                                                   plan-type="month"
                                                                   plan-price="{{$planPrices['pro-monthly']}}"
                                                                   plan-slug="pro-monthly">
                                                            <span>Sign me up!</span>
                                                        </label>
                                                    </div>
                                                    <div class="tab-pane fade" id="tab-pro-year">

                                                        <ul class="description">
                                                            <li class="bestvalue"><h4>You save <strong>10%</strong></h4>
                                                            </li>

                                                            <li><strong>Unlimited</strong> campaigns</li>
                                                            <li><strong>Unlimited</strong> content launches</li>
                                                            <li><strong>Unlimited</strong> tasks, ideas, and calendars
                                                            </li>
                                                            <li><h4>Up to <strong>10</strong> users</h4></li>
                                                        </ul>

                                                        <div class="amount">
                                                            <span class="dollar">$</span>
                                                            <span class="number">{{ number_format($planPrices['pro-annually']) }}</span>
                                                        </div>
                                                        <p class="amount-info">Paid annaully</p>

                                                        <label for="plan-4" class="checkbox-tag plan">
                                                            <input id="plan-4" type="checkbox" plan-name="pro"
                                                                   plan-type="year"
                                                                   plan-price="{{ $planPrices['pro-annually'] }}"
                                                                   plan-slug="pro-annually">
                                                            <span>Sign me up!</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {!! Form::open([ 'id'=>'subscriptionForm', 'route' => 'subscription' ]) !!}


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


                            <div class="input-form-group">
                                <div class="row">
                                    <div class="col-md-6 col-md-offset-2">
                                        <label for="auto_renew" class="checkbox-tag">
                                            <input id="auto_renew" type="checkbox" name="auto_renew"
                                                   value="1">
                                            <span>Auto Renew</span>
                                        </label>
                                    </div>
                                    <div class="col-md-6 col-md-offset-2">
                                        <input
                                                type="submit"
                                                id="submit-btn"
                                                class='button button-primary button-extend text-uppercase'
                                                value='Submit Order'>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-10">
                                <div class="input-form-group loading" style='display:none'>
                                    <img src="/images/ring.gif" class='loading-relative' alt="">
                                </div>
                            </div>

                            {!! Form::close() !!}

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