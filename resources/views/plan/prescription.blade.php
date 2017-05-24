@extends('layouts.master')


@section('content')
    <div class="workspace">
        <div class="panel">
            <div class="panel-header">
                <ul class="panel-tabs text-center">
                    <li>
                        <a href="/plan">Topic Generator</a>
                    </li>
                    <li>
                        <a href="/plan/trends">Content Trends</a>
                    </li>
                    <li class="active">
                        <a href="/plan/prescription">Content Prescription</a>
                    </li>
                    <li>
                        <a href="/plan/ideas">Ideas</a>
                    </li>
                </ul>
            </div>
            <div class="row prescription">

                <div class="col-md-8 col-md-offset-2">
                    <h5 class="heading-border text-center">
                        Please complete the form and we’ll suggest a content program
                        <i class="popover-icon icon-question"
                           data-toggle="popover"
                           title="Content Prescription"
                           data-content="The Content Launch team reviews your goals, budget and the type of company you are, and based on this, provides the best content recommendations to improve your marketing goals"
                           data-placement="bottom"
                        ></i>
                    </h5>

                    <form action="#" method="post">

                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="row">

                                    <div class="col-md-12">
                                        <label for="#">COMPANY TYPE</label>

                                        <div class="input-form-group">
                                            <div class="col-md-2">
                                                <label for="B2C" class="radio-secondary">
                                                    <input id="B2C" type="radio" name="company-type" value="B2C"
                                                           checked>
                                                    <span>B2C</span>
                                                </label>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="B2B" class="radio-secondary">
                                                    <input id="B2B" type="radio" name="company-type" value="B2B">
                                                    <span>B2B</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="input-form-group">
                                            <label for="#">WHAT ARE YOUR GOALS?</label>
                                            <div class="select">
                                                <select name="goals" id="goalsSelect">
                                                    <option value="" disabled selected>Select Goal</option>
                                                    <option value="traffic-from-search-engines">Traffic from Search
                                                        Engines
                                                    </option>
                                                    <option value="lead-generation">Lead Generation</option>
                                                    <option value="converting-leads-into-customers">Converting Leads
                                                        Into Customers
                                                    </option>
                                                    <option value="branding">Branding</option>
                                                    <option value="thought-leadership">Thought Leadership</option>
                                                    <option value="customer-loyalty">Customer Retention/Loyalty</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="input-form-group">
                                            <label for="#">WHAT IS YOUR MONTHLY BUDGET</label>
                                            <div class="select">
                                                <select name="monthly-budget">
                                                    <option value="" disabled selected>Select amount range</option>
                                                    <option value="500">$500</option>
                                                    <option value="1000">$1.000</option>
                                                    <option value="2000">$2.000</option>
                                                    <option value="4000">$4.000</option>
                                                    <option value="6000">$6.000</option>
                                                    <option value="8000">$8.000</option>
                                                    <option value="16000">$16.000</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="input-form-group">
                                    <label for="#">COMPANY NAME</label>
                                    <input type="text" class="input" placeholder="Enter company name">
                                </div>

                                <div class="input-form-group text-right">
                                    <button class="button button-primary">SUBMIT</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts')
    <script>
        $(function () {
            $('[data-toggle="popover"]').popover();

            handleGoals();
        });

        $('input[name=company-type]').change(function () {
            handleGoals();
        });

        function handleGoals() {
            let customerLoyaltyOption = $("#goalsSelect").find("option[value='customer-loyalty']");
            if ($('input[name=company-type]:checked').val() === 'B2B') {
                customerLoyaltyOption.hide();
            } else {
                customerLoyaltyOption.show();
            }
        }
    </script>
@endsection