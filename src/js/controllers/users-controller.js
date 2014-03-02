launch.module.controller('UsersController', [
	'$scope', '$location', '$filter', 'UserService', function ($scope, $location, $filter, UserService) {
		$scope.title = 'This is the users page controller';
		$scope.users = [];
		$scope.filteredUsers = [];

		$scope.search = {
			searchTerm: null,
			searchTermMinLength: 1,
			userStatus: 'active',
			toggleStatus: function(status) {
				this.userStatus = status;
			}
		};

		$scope.pagination = {
			totalItems: 0,
			pageSize: 2,
			currentPage: 1,
			currentSort: null,
			currentSortDirection: 'ASC',
			onPageChange: function (page) {
				// IF WE WANT TO PAGE FROM THE SERVER, ENTER THAT CODE AND
				// REMOVE THE getPagedUsers FUNCTION BELOW. ALSO, WE'LL NEED
				// TO TWEAK THE WHAT THAT pagination.totalItems IS CALCULATED
				// SUCH THAT THIS VALUE COMES BACK IN THE JSON RESPONSE.
			},
			showPager: function () {
				return (this.totalItems > this.pageSize);
			}
		};

		$scope.users = UserService.query(null, {
			success: function (users) {
				$scope.pagination.totalItems = $scope.users.length;
			}
		});

		$scope.applyFilter = function (user) {
			if (!launch.utils.isBlank($scope.search.searchTerm) && $scope.search.searchTerm.length >= $scope.search.searchTermMinLength) {
				return  (launch.utils.isBlank($scope.search.searchTerm) ? true : user.matchSearchTerm($scope.search.searchTerm));
			}

			return true;
		};

		$scope.$watch('search.searchTerm', function () {
			if (!launch.utils.isBlank($scope.search.searchTerm) && $scope.search.searchTerm.length >= $scope.search.searchTermMinLength) {
			}
		}, true);
	}
]);
