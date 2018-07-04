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
                        Simply complete the form and we'll suffuse a content program 
                         <i class="popover-icon icon-question"
                           data-toggle="popover"
                           title="How does Content Prescription Work?"
                           data-content="Our algorithm looks at your goals, budgets, and your company type, and then provides the best content recommendations to improve your content marketing program."
                           data-placement="bottom"
                        ></i>
                    </h5>

                    @if ($errors->any())
                        <div class="col-md-8 col-md-offset-2">
                            <div class="alert alert-danger alert-forms" id="formError">
                                <p><strong>Oops! We had some errors:</strong>
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                </p>
                            </div>
                        </div>
                    @endif

                    <form action="{{route('getPrescription')}}" method="post">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

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
                                                <select name="goal" id="goalsSelect" required>
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
                                            <label for="#">WHAT IS YOUR MONTHLY BUDGET FOR CONTENT PRODUCTION?</label>
                                            <div class="select">
                                                <select name="monthly-budget" required>
                                                    <option value="" disabled selected>Select amount range</option>
                                                    <option value="500.00">$500</option>
                                                    <option value="1000.00">$1K</option>
                                                    <option value="2000.00">$2K</option>
                                                    <option value="4000.00">$4K</option>
                                                    <option value="6000.00">$6K</option>
                                                    <option value="8000.00">$8K</option>
                                                    <option value="16000.00">$16K</option>
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
                                    <label for="#">URL</label>
                                    <input type="text" class="input" placeholder="Paste URL" name="url">
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