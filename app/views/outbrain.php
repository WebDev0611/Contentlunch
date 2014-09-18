<html>
	<head>
		<title></title>
	</head>
	<?php
		/*
		$url = 'google.com';
		*/
//		$url = urlencode('themindunleashed.org/2014/07/11-ways-live-happier-life-according-psychologist-hint-nothing-money.html');
		/*
		$url = 'http://themindunleashed.org/2014/07/11-ways-live-happier-life-according-psychologist-hint-nothing-money.html';
		$url = 'http://themindunleashed.org/2014/09/new-transparent-solar-concentrator-turn-window-power-source.html';
		$url = 'http://handsinthefire2.blogspot.com/2014/07/social-hierarchies-of-schoolyard_87.html';
		*/
	?>
	<body>
		<div class="modal-header">Outbrain Amplify</div>
		<div class="modal-body">
			<p><b>Link: </b> <?php print $url; ?></p>
			<p>Click the amplify button to send this link to your Outbrain campaign</p>
			<div class="OB_AMPLIFY" data-src="<?php print $url; ?>" data-channel-id="6"></div>
		</div>
		<div class="modal-footer">
		    <button class="btn btn-warning btn-black" ng-click="cancel()">Cancel</button>
		</div>

		<script type="text/javascript">
			(function() {
				var ob = document.createElement("script");
				ob.type = "text/javascript"; 
				ob.async = true;
				ob.src = "https://widgets.outbrain.com/amplifyThis/amplify.js";
				var s = document.getElementsByTagName('script')[0]; 
				s.parentNode.insertBefore(ob, s);
			})();
		</script>
	</body>
</html>
