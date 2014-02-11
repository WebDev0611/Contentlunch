<!DOCTYPE html>
<html lang="en" ng-app="cl">
<head>

<meta charset="utf-8" />
<title>Content Launch</title>
<meta name="keywords" content="Content Launch" />
<meta name="description" content="Content Launch" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" type="text/css" href="/assets/styles/app.css" />

<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<link rel="shortcut icon" href="/favicon.ico">

</head>
<body>

<header class="header">
	<div class="navbar navbar-inverse navbar-fixed-top" >
    <div class="navbar-inner">
      <div class="container">
        
        <div class="logo">
          <a href="/">
            <img src="/images/popwords-logo.png" alt="Content Launch" />
          </a>
        </div>

        <h2 class="nav-title" ng-bind="title"></h2>

        <div class="pull-right">
          <p class="navbar-text">
            <a href="/#/login">Login</a>
          </p>
        </div>

      </div>
    </div>
  </div>
</header>

<div class="container main">
	<div class="row" ng-view></div>
</div>



<div class="container">
	<footer>
	</footer>
</div>

<script type="text/javascript" src="/assets/scripts/app.js"></script>

</body>
</html>
