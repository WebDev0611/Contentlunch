<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Content Launch</title>
    <meta name="keywords" content="Content Launch" />
    <meta name="description" content="Content Launch" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="http://contentlaunch.com/favicon.ico" type="image/vnd.microsoft.icon" />

    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-components.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/angular-ui.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/main.css" />

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- load old version of jquery in no conflict mode -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.js" type="text/javascript"></script>
    <script type="text/javascript">
        $jquery1_11_1 = $.noConflict(true);
    </script>

</head>
<body>

<div class="row">
    <div class="col-md-4 col-md-offset-3 col-sm-10 col-sm-offset-1">
        {{ Form::open(array('url' => 'signup')) }}
        <h1>Sign Up</h1>

        <!-- if there are login errors, show them here -->
        @if (Session::get('signupError'))
        <div class="alert alert-danger">{{ Session::get('signupError') }}</div>
        @endif

        <div>
            {{ $errors->first('full_name') }}
            {{ $errors->first('name') }}
            {{ $errors->first('email') }}
            {{ $errors->first('password') }}
        </div>


        <div class="form-group">
            {{ Form::label('full_name', 'Full Name') }}
            {{ Form::text('full_name', Input::old('full_name'), ['placeholder' => 'your name', 'class' => 'form-control']) }}
        </div>


        <div class="form-group">
            {{ Form::label('name', 'Company Name') }}
            {{ Form::text('name', Input::old('name'), ['placeholder' => 'company name', 'class' => 'form-control']) }}
        </div>


        <div class="form-group">
            {{ Form::label('email', 'Email Address') }}
            {{ Form::text('email', Input::old('email'), ['placeholder' => 'email', 'class' => 'form-control']) }}
        </div>


        <div class="form-group">
            {{ Form::label('password', 'Password') }}
            {{ Form::password('password', ['class' => 'form-control']) }}
        </div>

        <label class="control-label">How will you use Content Launch?</label>

        <div>
            <label>
                <input type="radio"
                       name="optionsRadios"
                       id="optHowUse1" value="single" checked>
                To market my company.
            </label>
        </div>
        <div>
            <label>
                <input type="radio" name="optionsRadios" id="optHowUse2" value="agency" >
                To market one or more of my clients (Agency Mode)
            </label>
        </div>

        <hr/>

        <p class="center">By clicking Sign Up, you agree to our <a href="terms.html">Terms & Conditions.</a></p>

        <p>{{ Form::submit('Sign Up', ['class' => 'btn btn-default']) }}</p>
        {{ Form::close() }}
    </div>
</div>
</body>
</html>





