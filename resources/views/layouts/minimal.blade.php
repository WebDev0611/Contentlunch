<!DOCTYPE html>
<html>
<head lang="en">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>Content Launch</title>

    <meta name="description" content="Content Launch">
    <meta name="viewport" content="initial-scale=1.0,width=device-width">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" >

    <link rel="stylesheet" href="/css/main.css">

</head>
<body>

@include('partials.flash')
@yield('content')

<script src="/js/vendor.js"></script>
<script src="/js/plugins.js"></script>
<script src="/js/app.js"></script>

@yield('scripts')

@include('layouts.partials.js_user')
@include('layouts.partials.intercom')
@include('layouts.partials.fullstory')
@include('layouts.partials.google-analytics')

</body>
</html>
