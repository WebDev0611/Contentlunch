<!DOCTYPE html> 
<html> 
<head lang=en> <meta charset=utf-8> 
<meta http-equiv=X-UA-Compatible content="IE=edge,chrome=1"> 
<title>Content Launch</title> 
<meta name=description content="Content Launch"> 
<meta name=viewport content="initial-scale=1.0,width=device-width"> 
<link rel=stylesheet href=styles/main.css> 
</head> 
<body> 
@include('2016.partials.navigation')

@include('elements.navigation')

@include('elements.searchbar')

@yield('content')

<script src="/assets/js/vendor-2016.js"></script>
<script src="/assets/js/plugins-2016.js"></script>
<script src="/assets/js/app-2016.js"></script>

</body>
</html>