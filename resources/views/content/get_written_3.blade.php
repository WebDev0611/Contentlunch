@extends('layouts.master')

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
                            <h4 class="purchase-title">Make deposit via Stripe</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="#">CARD NUMBER</label>
                                        <input type="text" class="input" placeholder="Card number">
                                    </div>
                                    <div class="form-group">
                                        <label for="#">NAME ON CARD</label>
                                        <input type="text" class="input" placeholder="Name on Card">
                                    </div>
                                    <div class="input-form-group">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label for="#">VALID UNTIL</label>
                                                <input type="text" class="input" placeholder="MM/YYYY">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="#">CVV</label>
                                                <input type="text" class="input">
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
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <a href="/#/create/5" class="button button-primary button-extend text-uppercase">Submit Order</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop