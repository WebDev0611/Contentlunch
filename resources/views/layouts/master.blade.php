<!DOCTYPE html> 
<html> 
<head lang=en> <meta charset=utf-8> 
<meta http-equiv=X-UA-Compatible content="IE=edge,chrome=1"> 
<title>Content Launch</title> 
<meta name=description content="Content Launch"> 
<meta name=viewport content="initial-scale=1.0,width=device-width"> 
<link rel=stylesheet href=/css/main.css>
<script src="/js/vendor.js"></script>
</head> 
<body> 

@if( @is_agency )
	@include('elements.navigation-agency')
	@include('elements.searchbar-agency')
@else
	@include('elements.navigation')
	@include('elements.searchbar')
@endif


@include('partials.flash')
@yield('content')

<script src="/js/plugins.js"></script>
<script src="/js/app.js"></script>
<!-- Page Specific JS -->
@yield('scripts')

<!-- Create overlay -->
<div class="create-overlay">
  <div class="inner">
    
    <ul class="list-inline list-createmenu">
      <li class="first">
        <a href="#">
          <i class="icon-idea"></i>
          <p class="title">New Idea</p>
          <p>Suspendisse tincidunt eu lectus nec vestibulum. Etiam eget dolor...</p>
        </a>
      </li>
      <li class="second">
        <a href="#">
          <i class="icon-content-alert"></i>
          <p class="title">Content</p>
          <p>Suspendisse tincidunt eu lectus nec vestibulum. Etiam eget dolor...</p>
        </a>
      </li>
      <li class="third">
        <a href="#">
          <i class="icon-alert"></i>
          <p class="title">Campaign</p>
          <p>Suspendisse tincidunt eu lectus nec vestibulum. Etiam eget dolor...</p>
        </a>
      </li>
      <li class="fourth">
        <a href="#">
          <i class="icon-calendar"></i>
          <p class="title">Calendar Entry</p>
          <p>Suspendisse tincidunt eu lectus nec vestibulum. Etiam eget dolor...</p>
        </a>
      </li>
    </ul>
    
  </div>
</div>

</body>
</html>