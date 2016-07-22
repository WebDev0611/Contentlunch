<!DOCTYPE html> 
<html> 
<head lang=en> <meta charset=utf-8> 
<meta http-equiv=X-UA-Compatible content="IE=edge,chrome=1"> 
<title>Content Launch</title> 
<meta name=description content="Content Launch"> 
<meta name=viewport content="initial-scale=1.0,width=device-width"> 
<link rel=stylesheet href=/css/main.css>
</head> 
<body> 
@include('partials.navigation')

@include('elements.navigation')

@include('elements.searchbar')

@include('partials.flash')
@yield('content')

<script src="/js/vendor.js"></script>
<script src="/js/plugins.js"></script>
<script src="/js/app.js"></script>
<!-- Page Specific JS -->
@yield('scripts')

</body>
</html>