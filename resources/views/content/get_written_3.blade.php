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
                                        <span>Blog Post</span>
                                    </div>
                                </div>
                                <h4>Blog post on Car Sales industry, including proofreading</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <span>
                                            DUE DATE
                                            <strong>03/10/2016</strong>
                                        </span>
                                    </div>
                                    <div class="col-md-4">
                                        <span>
                                            AUTHOR
                                            <strong>Author Name</strong>
                                        </span>
                                    </div>
                                    <div class="col-md-4">
                                        <span>
                                            WORD COUNT
                                            <strong>330 words</strong>
                                        </span>
                                    </div>
                                </div>
                                <hr>
                                <a href="#fullDetails" data-toggle="collapse" class="purchase-order-more">
                                    <i><span class="caret"></span></i>
                                    Full Details
                                </a>
                                <div class="collapse" id="fullDetails">

                                </div>
                            </div>
                            <h4 class="purchase-title">Assignment Cost</h4>
                            <table class="purchase-order">
                                <tbody>
                                    <tr>
                                        <td>330 words article</td>
                                        <td>$115.00</td>
                                    </tr>
                                    <tr>
                                        <td>Proofreading 330 words article</td>
                                        <td>$55.00</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>TOTAL</td>
                                        <td>$170.00</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Deposit</strong></td>
                                        <td><strong>$50.00</strong></td>
                                    </tr>
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