
launch.module.controller('WriterAccessController', [
	'$scope', '$location', 'AuthService', 'AccountService', 'NotificationService', 'WriterAccessService', function ($scope, $location, authService, accountService, notificationService, writerAccessService) {
		var self = this,
			currentFlow = 1;

		self.init = function () {

		};

		$scope.orderFormData = {};
		$scope.categories = {};
		$scope.expertises = {};
		$scope.assetTypes = {};
		$scope.writerLevels = [4, 5, 6];
		$scope.wordcounts = [];
		$scope.price = 0;


		$scope.getScope = function(){
			return $scope;
		};

		$scope.orderFlow = function(newFlow){
			$("#order-flow-"+currentFlow).hide();
			currentFlow = newFlow;
			$("#order-flow-"+newFlow).show();
		};

		$scope.getOrder = function(id){
			var orderResponse = writerAccessService.order({id:id}, function() {
				$scope.orders = orderResponse.orders;
			});
		};

		$scope.getOrders = function(){
			var orderResponse = writerAccessService.orders(function() {
				$scope.orders = orderResponse.orders;
			});
		};

		$scope.getAssetTypes = function(){
			var assetTypeResponse = writerAccessService.assetTypes(function() {
				$scope.assetTypes = assetTypeResponse.writerAccessAssetTypes;
				$scope.orderFormData.assetType = 0;
				$scope.wordcounts = $scope.assetTypes[0].wordcounts;
				$scope.orderFormData = {"assetType":0,"wordcount":"500","writer_level":"5","duedate":"5/30/2016","title":"Test - Order","target":"Everyone","instructions":"Please ignore this order","tone":"Writing tone goes here","Voice":"Professional on the topic","voice":"Voice of God"}
				$scope.updatePrice();
			});
		};

		$scope.getFormOptions = function(){
			$scope.getAssetTypes();
		};

		$scope.updatePrice = function(){


			var assetType = $scope.assetTypes[$scope.orderFormData.assetType],
				prices = assetType.prices;

			$scope.wordcounts = assetType.wordcounts;

			console.log(prices.length);

			prices = prices.filter(function(price){
				return price.wordcount === $scope.orderFormData.wordcount;
			});

			console.log(prices.length);

			prices = prices.filter(function(price){
				return price.writer_level === $scope.orderFormData.writer_level;
			});

			console.log(prices.length);

			$scope.price = prices[0] ? prices[0].fee : 0;
		};

		$scope.submitOrder = function(){
			// swap the assetType select index for the writeraccess assetType_id value
			console.log($scope.assetTypes[$scope.orderFormData.assetType].writer_access_id);
			console.log($scope.orderFormData.assetType);
			$scope.orderFormData.assetType = $scope.assetTypes[$scope.orderFormData.assetType].writer_access_id;
			console.log($scope.orderFormData.assetType);

			Stripe.setPublishableKey('pk_test_9WtB8kfnBxpSgEX7MMwOkA82');
			console.log("submitting the order");
			// disable the submit button to prevent repeated clicks
			$('.submit-button').attr("disabled", "disabled");
			// createToken returns immediately - the supplied callback submits the form if there are no errors
			Stripe.createToken({
				number: $('.card-number').val(),
				cvc: $('.card-cvc').val(),
				exp_month: $('.card-expiry-month').val(),
				exp_year: $('.card-expiry-year').val()
			}, stripeResponseHandler);
			return false; // submit from callback

		};

		function stripeResponseHandler(status, response) {
			console.log(response);

			if (response.error) {
				// re-enable the submit button
				$('.submit-button').removeAttr("disabled");
				// show the errors on the form
				$(".payment-errors").html(response.error.message);
			} else {
				var $form = $("#orderForm");
				// token contains id, last4, and card type
				var token = response['id'];
				// insert the token into the form so it gets submitted to the server
				$form.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
				// and submit
				$form.get(0).submit();
			}
		}

		self.init();
	}
]);