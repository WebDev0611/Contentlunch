<!DOCTYPE html>
<html>
<head lang="en">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>Content Launch</title>

    <meta name="description" content="Content Launch">
    <meta name="viewport" content="initial-scale=1.0,width=device-width">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" >

    <link rel="stylesheet" href="{{ elixir('css/main.css', null) }}">

</head>
<body>

@include('partials.flash')
@yield('content')

<script src="{{ elixir('js/vendor.js', null) }}"></script>
<script src="{{ elixir('js/vue.js', null) }}"></script>
<script src="{{ elixir('js/plugins.js', null) }}"></script>
<script src="{{ elixir('js/app.js', null) }}"></script>

@yield('scripts')

@include('layouts.partials.js_user')
@include('layouts.partials.intercom')
@include('layouts.partials.fullstory')
@include('layouts.partials.google-analytics')

</body>
</html>
