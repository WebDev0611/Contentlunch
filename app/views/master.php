<!DOCTYPE html>
<html lang="en" ng-app="launch">
<head>
    <meta charset="utf-8" />
    <title>Pop</title>
    <meta name="keywords" content="Content Launch" />
    <meta name="description" content="Content Launch" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/pnotify.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/main.css" />

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link rel="shortcut icon" href="/favicon.ico">
</head>
<body>
	<header ng-controller="NavigationController" ng-cloak navigation-template></header>
	
    <div class="main-content" ng-view></div>

    <footer></footer>

    <script type="text/javascript" src="/assets/js/build.js"></script>
    <script type="text/javascript" src="/assets/js/app.js"></script>
</body>
</html>