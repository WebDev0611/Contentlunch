launch.module.controller('CreateController', [
	'$scope', '$location', 'AuthService', function ($scope, $location, authService) {
		var self = this;

		self.loggedInUser = null;

		self.init = function () {
			self.loggedInUser = authService.userInfo();

			$scope.contentTypes = launch.config.CONTENT_TYPES;
		};

		$scope.contentTypes = null;

		$scope.search = {
			searchTerm: null,
			searchTermMinLength: 1,
			myTasks: false,
			contentTypes: null,
			applyFilter: function (reset) {
				//$scope.filteredAccounts = $filter('filter')($scope.accounts, function (account) {
				//	if ($scope.search.accountStatus === 'all' || ($scope.search.accountStatus === 'active' && account.active) || ($scope.search.accountStatus === 'inactive' && !account.active)) {
				//		if (!launch.utils.isBlank($scope.search.searchTerm) && $scope.search.searchTerm.length >= $scope.search.searchTermMinLength) {
				//			return (launch.utils.isBlank($scope.search.searchTerm) ? true : account.matchSearchTerm($scope.search.searchTerm));
				//		} else {
				//			return true;
				//		}
				//	} else {
				//		return false;
				//	}
				//});

				//if (reset === true) {
				//	$scope.pagination.currentPage = 1;
				//}

				//$scope.pagination.totalItems = $scope.filteredAccounts.length;
				//$scope.pagination.groupToPages();

				//if (!!$scope.selectedAccount) {
				//	if (self.accountExists($scope.selectedAccount, $scope.filteredAccounts)) {
				//		self.adjustPage($scope.selectedAccount.id, null);
				//	} else {
				//		self.reset();
				//	}
				//}
			}
		};

		$scope.formatContentTypeItem = function (item, element, context) {
			return '<span class="' + launch.utils.getContentTypeIconClass(item.id) + '"></span> <span>' + item.text + '</span>';
		};

		self.init();
	}
]);