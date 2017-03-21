@extends('layouts.minimal')

@section('content')

    <div class="landing">
        <a href="#" class="landing-logo">
            <img src="/images/logo-full.svg" alt="#">
        </a>
        <div class="container-fluid">

            <div class="onboarding-container narrow">

                <div class="inner supernarrow">
                    <h3 class="onboarding-step-text text-center">
                        Password Reset
                    </h3>


                    <div class="body">
                        @if (session('status'))
                            <div class="alert alert-success alert-forms">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                            {{ csrf_field() }}

                            <div class="input-form-group tight {{ $errors->has('email') ? 'has-error' : '' }}">
                                <label for="#">Email Address</label>
                                <div class="form-suffix form-suffix-login">
                                    <i class="icon-email picto"></i>
                                    <input  id="email"  name="email" type="email" class="input full" placeholder="Email address" value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-envelope"></i> Send Password Reset Link
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>


                </div>
            </div>


        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        var emailInput = $("#email");

        $('form').on('submit', function(e) {
            $('.email-error').remove();

            if (!regex.test(emailInput.val())) {
                e.preventDefault(e);
                emailInput.closest('.input-form-group').addClass('has-error');
                emailInput.after($( '<span class="help-block email-error"><strong>Invalid email address</strong></span>'));
            }
        });
    </script>
@endsection
