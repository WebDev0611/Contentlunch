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

                                    <div class="plan-selector">
                                        <h1>Basic</h1>

                                        <div class="panel with-nav-tabs panel-default">
                                            <div class="panel-heading">
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a href="#tab-basic-month" data-toggle="tab"><h5>
                                                                Monthly</h5></a></li>
                                                    <li><a href="#tab-basic-year" data-toggle="tab"><h5>Annually</h5>
                                                        </a></li>
                                                </ul>
                                            </div>
                                            <div class="panel-body">
                                                <div class="tab-content">
                                                    <div class="tab-pane fade in active" id="tab-basic-month">
                                                        <div class="amount">
                                                            <span class="dollar">$</span>
                                                            <span class="number">99</span>
                                                        </div>
                                                        <p class="amount-info"></p>

                                                        <label for="plan-1" class="checkbox-tag">
                                                            <input id="plan-1" type="checkbox" plan-name="basic"
                                                                   plan-type="month">
                                                            <span>Sign me up!</span>
                                                        </label>
                                                    </div>
                                                    <div class="tab-pane fade" id="tab-basic-year">
                                                        <div class="amount">
                                                            <span class="dollar">$</span>
                                                            <span class="number">1,069</span>
                                                        </div>
                                                        <p class="amount-info"></p>

                                                        <label for="plan-2" class="checkbox-tag">
                                                            <input id="plan-2" type="checkbox" plan-name="basic"
                                                                   plan-type="year">
                                                            <span>Sign me up!</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">

                                    <div class="plan-selector">
                                        <h1>Pro</h1>

                                        <div class="panel with-nav-tabs panel-default">
                                            <div class="panel-heading">
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a href="#tab-pro-month" data-toggle="tab"><h5>
                                                                Monthly</h5></a></li>
                                                    <li><a href="#tab-pro-year" data-toggle="tab"><h5>Annually</h5></a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="panel-body">
                                                <div class="tab-content">
                                                    <div class="tab-pane fade in active" id="tab-pro-month">
                                                        <div class="amount">
                                                            <span class="dollar">$</span>
                                                            <span class="number">199</span>
                                                        </div>
                                                        <p class="amount-info"></p>

                                                        <label for="plan-3" class="checkbox-tag">
                                                            <input id="plan-3" type="checkbox" plan-name="pro"
                                                                   plan-type="month">
                                                            <span>Sign me up!</span>
                                                        </label>
                                                    </div>
                                                    <div class="tab-pane fade" id="tab-pro-year">
                                                        <div class="amount">
                                                            <span class="dollar">$</span>
                                                            <span class="number">2,149</span>
                                                        </div>
                                                        <p class="amount-info"></p>

                                                        <label for="plan-4" class="checkbox-tag">
                                                            <input id="plan-4" type="checkbox" plan-name="pro"
                                                                   plan-type="year">
                                                            <span>Sign me up!</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>


                            <div class="col-md-8">

                                {!! Form::open([ 'id'=>'subscriptionForm', 'route' => 'subscription' ]) !!}
                                <div class="row">


                                    <div class="col-md-10 col-md-offset-1">
                                        <div class="purchase-assignment">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span>Assignment:</span>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <span>XXXXXXXXXXXX</span>
                                                </div>
                                            </div>
                                            <h4>XXXXXXXXXXX</h4>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <span>
                                                        DUE DATE
                                                        <strong>XXXXXXXXXX</strong>
                                                    </span>
                                                </div>
                                                <div class="col-md-4">
                                                    <span>
                                                        AUTHOR
                                                        <strong>XXXXXXXXX</strong>
                                                    </span>
                                                </div>
                                                <div class="col-md-4">
                                                    <span>
                                                        WORD COUNT
                                                        <strong>XXXXXXXXXXXXXXX</strong>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="auto_renew" class="checkbox-primary text-inline">
                                                        <input id="auto_renew" type="checkbox" name="auto_renew"
                                                               value="1">
                                                        <span>Auto Renew</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <input type="hidden" name="stripe-customer-id"
                                                   value="{{$user->stripe_customer_id}}">

                                        </div>


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
                                        </div>
                                    </div>
                                </div>
                                <div class="input-form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-md-offset-3">
                                            <input
                                                    type="submit"
                                                    id="submit-btn"
                                                    class='button button-primary button-extend text-uppercase'
                                                    value='Submit Order'>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-form-group loading" style='display:none'>
                                    <img src="/images/ring.gif" class='loading-relative' alt="">
                                </div>
                                {!! Form::close() !!}

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
        (function () {
            $('#submit-btn').prop('disabled', true);
            Stripe.setPublishableKey("{{ getenv('STRIPE_PUBLISHABLE_KEY') }}");

            function stripeResponseHandler(status, response) {
                var $form = $('#subscriptionForm');

                if (response.error) {
                    $form.find('input[type=submit]').prop('disabled', false);
                    $('#paymentErrors')
                            .text(response.error.message)
                            .slideDown('fast');
                    $('.loading').fadeOut('fast');
                    $('#submit-btn').prop('disabled', false);
                } else {
                    var token = response.id;
                    $form.append($('<input type="hidden" name="stripe-token" />').val(token));
                    $form.get(0).submit();
                }
            }

            $('#subscriptionForm').submit(function (e) {
                var $form = $(this);

                $('#submit-btn').prop('disabled', true);
                $('.loading').fadeIn('fast');

                Stripe.card.createToken($form, stripeResponseHandler);

                return false;
            });

            // Allow only 1 subscription plan to be selected
            $('.checkbox-tag input[type="checkbox"]').on('change', function () {
                $('.checkbox-tag input[type="checkbox"]').not(this).prop('checked', false);

                var $form = $('#subscriptionForm');
                if (this.checked) {
                    if (!$('input[name="plan-name"]').val() && !$('input[name="plan-type"]').val()) {
                        $form.append($('<input type="hidden" name="plan-name" />'));
                        $form.append($('<input type="hidden" name="plan-type" />'));
                    }
                    $('input[name="plan-name"]').val($(this).attr('plan-name'));
                    $('input[name="plan-type"]').val($(this).attr('plan-type'));

                    $('#submit-btn').show();
                    $('#submit-btn').prop('disabled', false);
                } else {
                    $('input[name="plan-name"]').remove();
                    $('input[name="plan-type"]').remove();
                    $('#submit-btn').prop('disabled', true);
                }
            });
        })();
    </script>
@stop