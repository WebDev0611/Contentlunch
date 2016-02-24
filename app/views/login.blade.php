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
                {{ Form::open(array('url' => 'login')) }}
                <h1>Login</h1>

                <!-- if there are login errors, show them here -->
                @if (Session::get('loginError'))
                <div class="alert alert-danger">{{ Session::get('loginError') }}</div>
                @endif

                <div>
                    {{ $errors->first('email') }}
                    {{ $errors->first('password') }}
                </div>

                <div class="form-group">
                    {{ Form::label('email', 'Email Address') }}
                    {{ Form::text('email', Input::old('email'), array('class'=>'form-control', 'placeholder' => 'awesome@awesome.com')) }}
                </div>

                <div class="form-group">
                    {{ Form::label('password', 'Password') }}
                    {{ Form::password('password', ['class'=>'form-control']) }}
                </div>

                <p>{{ Form::submit('Submit!', ['class'=>'btn btn-default']) }}</p>
                {{ Form::close() }}
            </div>
        </div>
    </body>
</html>