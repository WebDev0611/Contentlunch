<!DOCTYPE html>
<html>
<head lang="en">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>Content Launch</title>

    <meta name="description" content="Content Launch">
    <meta name="viewport" content="initial-scale=1.0,width=device-width">

    <link rel="stylesheet" href="/css/main.css">

</head>
<body>

@include('partials.flash')
@yield('content')

<script src="/js/vendor.js"></script>
<script src="/js/plugins.js"></script>
<script src="/js/app.js"></script>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-92080489-1', 'auto');
    ga('send', 'pageview');

</script>
@yield('scripts')

@include('layouts.partials.js_user')
@include('layouts.partials.intercom')

</body>
</html>
