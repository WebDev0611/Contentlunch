<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ContentLaunch | Administrative Dashboard </title>

    <link rel="stylesheet" href="{!! asset('css/admin_vendor.css') !!}">
    <link rel="stylesheet" href="{!! asset('css/admin.css') !!}">
</head>

<body class="gray-bg">

<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        <div>
            <h1 class="logo-name">
                <img src="/images/logo.svg">
            </h1>
        </div>

        @include('admin.partials.flash')

        {{ Form::open([ 'url' => route('admin_login.login') ]) }}
            <div class="form-group @if($errors->has('email')) has-error @endif">
                {{ Form::text('email', old('email'), [ 'class' => 'form-control', 'placeholder' => 'Email' ]) }}
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group @if($errors->has('password')) has-error @endif">
                {{ Form::password('password', [ 'class' => 'form-control', 'placeholder' => 'Password']) }}
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
        {{ Form::close() }}

        <p class="m-t">
            <small>ContentLaunch &copy; 2016-2017</small>
        </p>
    </div>
</div>

<!-- Mainly scripts -->
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>

</html>
