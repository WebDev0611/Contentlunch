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
                                            <div class="form-group">
                                                <label for="AutoRenew" class="checkbox-primary text-inline">
                                                    <input id="AutoRenew" type="checkbox">
                                                    <span>Auto Renew</span>
                                                </label>
                                            </div>
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
        (function() {
            Stripe.setPublishableKey("{{ getenv('STRIPE_PUBLISHABLE_KEY') }}");

            function stripeResponseHandler(status, response) {
                var $form = $('#subscriptionForm');

                if (response.error) {
                    $form.find('input[type=submit]').prop('disabled', false);
                    $('#paymentErrors')
                            .text(response.error.message)
                            .slideDown('fast');
                    $('.loading').fadeOut('fast');
                } else {
                    var token = response.id;
                    $form.append($('<input type="hidden" name="stripe-token" />').val(token));
                    $form.get(0).submit();
                }
            }

            function disableForm($form) {
                $form.find('input').prop('disabled', true);
            }

            $('#subscriptionForm').submit(function(e) {
                var $form = $(this);

                disableForm($form);
                $('.loading').fadeIn('fast');

                Stripe.card.createToken($form, stripeResponseHandler);

                return false;
            });
        })();
    </script>
@stop