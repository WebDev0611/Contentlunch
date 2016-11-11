@extends('layouts.master')

@section('scripts.head')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@stop

@section('content')
<div class="workspace">
    <h4 class="text-center">Get Content Written</h4>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="onboarding-container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="create-step">
                                <span class="create-step-point active"></span>
                                <span class="create-step-point active"></span>
                                <span class="create-step-point active"></span>
                            </div>
                        </div>
                    </div>
                    {!! Form::open([ 'url' => "writeraccess/orders/$order->id/submit" ]) !!}
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="purchase-assignment">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span>Assignment:</span>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <span>{{ $order->assetType->name }}</span>
                                    </div>
                                </div>
                                <h4>{{ $order->project_name }}</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <span>
                                            DUE DATE
                                            <strong>{{ $order->duedate }}</strong>
                                        </span>
                                    </div>
                                    <div class="col-md-4">
                                        <span>
                                            AUTHOR
                                            <strong>{{ $order->user->name }}</strong>
                                        </span>
                                    </div>
                                    <div class="col-md-4">
                                        <span>
                                            WORD COUNT
                                            <strong>{{ $order->wordcount }} words</strong>
                                        </span>
                                    </div>
                                </div>
                                <!--
                                <hr>
                                <a href="#fullDetails" data-toggle="collapse" class="purchase-order-more">
                                    <i><span class="caret"></span></i>
                                    Full Details
                                </a>
                                <div class="collapse" id="fullDetails">

                                </div>
                                -->
                            </div>
                            <h4 class="purchase-title">Assignment Cost</h4>
                            <table class="purchase-order">
                                <tbody>
                                    <tr>
                                        <td>{{ $order->wordcount }} words article</td>
                                        <td>${{ $order->price }}.00</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>TOTAL</td>
                                        <td>${{ $order->price }}.00</td>
                                    </tr>
                                    {{--
                                    <tr>
                                        <td><strong>Deposit</strong></td>
                                        <td><strong>${{ $order->price}}.00</strong></td>
                                    </tr>
                                    --}}
                                </tfoot>
                            </table>
                            @if ($errors->any())
                                <div class="alert alert-danger alert-forms" id="formError">
                                    <p><strong>Oops! We had some errors:</strong>
                                        <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                        </ul>
                                    </p>
                                </div>
                            @endif

                            <div
                                id="paymentErrors"
                                class='alert alert-danger alert-forms'
                                style='display:none'></div>

                            <h4 class="purchase-title">Make deposit via Stripe</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-form-group">
                                        <label>CARD NUMBER</label>
                                        <input type="text" data-stripe='number' class='input' placeholder='Card Number'>
                                    </div>
                                    <div class="input-form-group">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label>Expiration month</label>
                                                <input type="text" data-stripe='exp-month' placeholder='MM' class='input'>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Expiration year</label>
                                                <input type="text" data-stripe='exp-year' placeholder='YYYY' class='input'>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="#">CVC</label>
                                                <input type="text" class="input" data-stripe='cvc'>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <img src="/images/stripe.png" alt="" class="img-responsive center-block">
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
                    {!! Form::close() !!}
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
            var $form = $('form');

            if (response.error) {
                $form.find('input[type=submit]').prop('disabled', false);
                $('#paymentErrors')
                    .text(response.error.message)
                    .slideDown('fast');
            } else {
                var token = response.id;
                $form.append($('<input type="hidden" name="stripeToken" />').val(token));
                $form.get(0).submit();
            }
        }

        $('form').submit(function(e) {
            var $form = $(this);

            $form.find('input[type=submit]').prop('disabled', true);

            Stripe.card.createToken($form, stripeResponseHandler);

            return false;
        });
    })();
</script>
@stop