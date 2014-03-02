launch.module.controller('UsersController', [
	'$scope', '$location', '$filter', 'UserService', function ($scope, $location, $filter, UserService) {
		$scope.users = [];
		$scope.filteredUsers = [];
		$scope.pagedUsers = [];

		$scope.search = {
			searchTerm: null,
			searchTermMinLength: 1,
			userStatus: 'active',
			toggleStatus: function (status) {
				this.userStatus = status;
			}
		};

		$scope.pagination = {
			totalItems: 0,
			pageSize: 2,
			currentPage: 1,
			currentSort: 'firstName',
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
				$scope.applyFilter();
			}
		});

		$scope.applyFilter = function () {
			$scope.filteredUsers = $filter('filter')($scope.users, function(user) {
				if (!launch.utils.isBlank($scope.search.searchTerm) && $scope.search.searchTerm.length >= $scope.search.searchTermMinLength) {
					return (launch.utils.isBlank($scope.search.searchTerm) ? true : user.matchSearchTerm($scope.search.searchTerm));
				}

				return true;
			});

			$scope.pagination.currentPage = 1;
			$scope.pagination.totalItems = $scope.filteredUsers.length;
			$scope.groupToPages();
		};

		$scope.groupToPages = function() {
			$scope.pagedUsers = [];

			for (var i = 0; i < $scope.filteredUsers.length; i++) {
				if (i % $scope.pagination.pageSize === 0) {
					$scope.pagedUsers[Math.floor(i / $scope.pagination.pageSize)] = [$scope.filteredUsers[i]];
				} else {
					$scope.pagedUsers[Math.floor(i / $scope.pagination.pageSize)].push($scope.filteredUsers[i]);
				}
			}
		};
	}
]);
