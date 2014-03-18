<!DOCTYPE html>
<html lang="en" ng-app="launch">
<head>
    <meta charset="utf-8" />
    <title>Pop</title>
    <meta name="keywords" content="Content Launch" />
    <meta name="description" content="Content Launch" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-components.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/main.css" />

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link rel="shortcut icon" href="/favicon.ico">
</head>
<body>
	<header ng-cloak navigation-template></header>
	
    <div class="main-content" ng-view></div>

    <footer></footer>

    <script type="text/javascript" src="/assets/js/build.js"></script>
	<script type="text/javascript" src="/assets/js/app.js"></script>

    <script type="text/ng-template" id="confirm-cancel.html">
	    <div class="modal-body">
		    You have not saved your changes. Are you sure you want to cancel?
	    </div>
	    <div class="modal-footer">
		    <button class="btn btn-default" ng-click="save()">Save Changes</button>
		    <button class="btn btn-default" ng-click="cancel()">Discard Changes</button>
	    </div>
    </script>

	<script type="text/ng-template" id="confirm-delete.html">
	    <div class="modal-body">
		    Are you sure you want to delete this {{ deleteType }}?
	    </div>
	    <div class="modal-footer">
		    <button class="btn btn-default" ng-click="delete()">Delete</button>
		    <button class="btn btn-default" ng-click="cancel()">Cancel</button>
	    </div>
    </script>
</body>
</html>