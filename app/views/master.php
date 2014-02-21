<!DOCTYPE html>
<html lang="en" ng-app="launch">
<head>
    <meta charset="utf-8" />
    <title>Pop</title>
    <meta name="keywords" content="Content Launch" />
    <meta name="description" content="Content Launch" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/main.css" />

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link rel="shortcut icon" href="/favicon.ico">
</head>
<body>
    <header class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div>
		    <nav class="navbar-collapse bs-navbar-collapse collapse" role="navigation">
			    <ul class="nav navbar-nav">
				    <li><a href="/">HOME</a></li>
				    <li><a href="/consult">CONSULT</a></li>
				    <li><a  href="/create">CREATE</a></li>
				    <li><a href="/calendar">CALENDAR</a></li>
				    <li><a href="/launch">LAUNCH</a></li>
				    <li><a href="/measure">MEASURE</a></li>
			    </ul>
		    </nav>
		    <div class="navbar-header">
			    <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#main-nav-items">
				    <span class="sr-only">Toggle navigation</span>
				    <span class="icon-bar"></span>
				    <span class="icon-bar"></span>
				    <span class="icon-bar"></span>
			    </button>
			    <a href="/" class="navbar-brand">ContentLaunch</a>
		    </div>
        </div>
	</header>

    <div class="container main">
        <div class="row" ng-view></div>
    </div>

    <footer>
    </footer>

    <script type="text/javascript" src="/assets/js/build.js"></script>
    <script type="text/javascript" src="/assets/js/app.js"></script>
</body>
</html>