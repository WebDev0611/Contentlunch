<!DOCTYPE html>
<html lang="en" ng-app="launch">
<head>
    <meta charset="utf-8" />
    <title>Content Launch</title>
    <meta name="keywords" content="Content Launch" />
    <meta name="description" content="Content Launch" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="http://contentlaunch.com/favicon.ico" type="image/vnd.microsoft.icon" />

	<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-components.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/angular-ui.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/main.css" />

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- load old version of jquery in no conflict mode -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.js" type="text/javascript"></script>
    <script type="text/javascript">
        $jquery1_11_1 = $.noConflict(true);
    </script>

</head>
<body onunload="">
	<header ng-cloak navigation-template></header>

    <div class="main-content" ng-view></div>

    <footer></footer>

    <script async type="text/javascript" src="/assets/js/build.js?version=<?php echo Config::get('app.version') ?>"></script>
    <script async type="text/javascript" src="https://www.googleadservices.com/pagead/conversion_async.js" charset="utf-8"></script>
    <script async type="text/javascript" src="https://checkout.stripe.com/checkout.js"></script>
    <script async type="text/javascript" src="/assets/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="/assets/js/app.js?version=<?php echo Config::get('app.version') ?>"></script>


      <script type="text/javascript">
      //var launchDebug = true;
      
          launch.module.constant('accountId', <?= $accountId ?>);

          <?php if (Config::get('app.debug')): ?>
              $(document).ready(function() {
                  window.launch.config.DEBUG_MODE = true;
              });
          <?php endif; ?>
      </script>



	<script type="text/ng-template" id="confirm.html">
		<div class="modal-body" ng-bind="message"></div>
	    <div class="modal-footer">
		    <button class="btn btn-default" ng-click="onOk()" ng-bind="okButtonText"></button>
		    <button class="btn btn-default" ng-click="onCancel()" ng-bind="cancelButtonText"></button>
	    </div>
	</script>

</body>
</html>
