@extends('layouts.minimal')

@section('content')

    <div class="landing" xmlns:v-on="http://www.w3.org/1999/xhtml">
        <a href="#" class="landing-logo">
            <img src="/images/logo-full.svg" alt="#">
        </a>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="onboarding-container" id="prelimContentScoreApp" >

                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">

                                <h5 class="onboarding-step-text text-center">
                                    Preliminary Content Score
                                </h5>

                                <div class="onboarding-step">
                                    <span class="onboarding-step-point active"></span>
                                    <span class="onboarding-step-point active"></span>
                                    <span class="onboarding-step-point active"></span>
                                    <span class="onboarding-step-point active"></span>
                                </div>

                                <h5 class="onboarding-text text-center">
                                    <span v-html="message"></span>
                                    <span class="loading-ring"><img src="/images/ring.gif" class="loading-relative" style="max-height: 30px;"></span>
                                </h5>



                                <div class="col-md-8 col-md-offset-2 ga-form">

                                    <div class="row add-connection hide">
                                        {{ Form::open([ 'route' => 'connections.store']) }}
                                        <div class="text-center">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" id="connectionType" name="con_type" value="google-analytics">

                                            @if ($errors->any())
                                                <div  class="alert alert-danger" id="formError">
                                                    <p><strong>Oops! We had some errors:</strong>
                                                    <ul>
                                                        @foreach($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                    </p>
                                                </div>
                                            @endif

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-form-group">
                                                        {{ Form::text('con_name', null, ['placeholder' => 'Name your connection here', 'class' => 'input', 'id' => 'con_name']) }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary connect-button">
                                                        Connect to Google Analytics
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        {{ Form::close() }}
                                    </div>

                                    <div class="row connection hide">
                                        <div class="input-form-group">
                                            <div class="select">
                                                <select @change="getAccounts($event.target.value)" name="ga_connection"></select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row account hide">
                                        <div class="input-form-group">
                                            <div class="select">
                                                <select @change="getProperties($event.target.value); setGaAccountName($event.target.item($event.target.selectedIndex).text);" name="ga_account"></select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row property hide">
                                        <div class="input-form-group">
                                            <div class="select">
                                                <select @change="getProfiles($event.target)" name="ga_property"></select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row profile hide">
                                        <div class="input-form-group">
                                            <div class="select">
                                                <select @change="getContentScore($event.target.value)" name="ga_profile"></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="row score hide">
                            <div class="col-md-8 col-md-offset-2 text-center">
                                <div class="onboarding-score">

                                    <div class="onboarding-score-chart">

                                        <div class="dashboard-donut-chart content-score donut-chart primary">
                                            <div class="slice one"></div>
                                            <div class="slice two"></div>
                                            <div class="slice three"></div>
                                            <div class="chart-center">
                                                <span id="contentScoreSpan"></span>
                                            </div>
                                        </div>

                                        <p>PRELIMINARY<br>CONTENT SCORE</p>

                                    </div>

                                </div>
                                {{--<p>
                                    This is your preliminary content score. By using {{ trans('messages.company') }} you can improve it.
                                </p>--}}
                            </div>
                        </div>

                        <div class="row feedback hide">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="onboarding-score-section-row text-center">
                                    <div class="onboarding-score-section col-md-6">
                                        <div class="onboarding-score-result positive">
                                            POSITIVE FEEDBACK:
                                        </div>
                                        <p>
                                            <span v-html="positiveFeedback"></span>
                                        </p>
                                    </div>
                                    <div class="onboarding-score-section col-md-6">
                                        <div class="onboarding-score-result negative">
                                            IMPROVEMENTS:
                                        </div>
                                        <p>
                                            <span v-html="negativeFeedback"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <a href="/" class="button button-extend text-uppercase">
                                    GET ME TO THE APP
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop


@section('scripts')
    <script src="{{ elixir('js/onboarding.js', null) }}"></script>
@stop

