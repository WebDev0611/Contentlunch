launch.module.controller('UsersController', [
	'$scope', '$location', '$filter', 'UserService', function($scope, $location, $filter, UserService) {
		$scope.users = [];
		$scope.filteredUsers = [];
		$scope.pagedUsers = [];
		$scope.selectedIndex = null;
		$scope.selectedUser = null;

		$scope.search = {
			searchTerm: null,
			searchTermMinLength: 1,
			userStatus: 'active',
			toggleStatus: function(status) {
				this.userStatus = status;
			},
			applyFilter: function(reset) {
				$scope.filteredUsers = $filter('filter')($scope.users, function(user) {
					if (!launch.utils.isBlank($scope.search.searchTerm) && $scope.search.searchTerm.length >= $scope.search.searchTermMinLength) {
						return (launch.utils.isBlank($scope.search.searchTerm) ? true : user.matchSearchTerm($scope.search.searchTerm));
					}

					return true;
				});

				if (reset === true) {
					$scope.pagination.currentPage = 1;
				}

				$scope.pagination.totalItems = $scope.filteredUsers.length;
				$scope.pagination.groupToPages();
			}
		};

		$scope.pagination = {
			totalItems: 0,
			pageSize: 2,
			currentPage: 1,
			currentSort: 'firstName',
			currentSortDirection: 'ASC',
			onPageChange: function (page) {
				$scope.selectUser();

				// IF WE WANT TO PAGE FROM THE SERVER, ENTER THAT CODE AND
				// REMOVE THE getPagedUsers FUNCTION BELOW. ALSO, WE'LL NEED
				// TO TWEAK THE WHAT THAT pagination.totalItems IS CALCULATED
				// SUCH THAT THIS VALUE COMES BACK IN THE JSON RESPONSE.
			},
			showPager: function() {
				return (this.totalItems > this.pageSize);
			},
			groupToPages: function() {
				$scope.pagedUsers = [];

				for (var i = 0; i < $scope.filteredUsers.length; i++) {
					if (i % $scope.pagination.pageSize === 0) {
						$scope.pagedUsers[Math.floor(i / $scope.pagination.pageSize)] = [$scope.filteredUsers[i]];
					} else {
						$scope.pagedUsers[Math.floor(i / $scope.pagination.pageSize)].push($scope.filteredUsers[i]);
					}
				}
			}
		};

		$scope.users = UserService.query(null, {
			success: function(users) {
				$scope.search.applyFilter(true);
			}
		});

		$scope.isSelectedUser = function (user) {
			if (!$scope.selectedUser || !user) {
				return false;
			}

			return (user.id === $scope.selectedUser.id);
		};

		$scope.enterNewUser = function() {
			$scope.selectedIndex = -1;
			$scope.selectedUser = UserService.getNewUser();
		};

		$scope.selectUser = function (user, i) {
			if (!user || $scope.selectedUser === user) {
				$scope.selectedIndex = null;
				$scope.selectedUser = null;
			} else {
				$scope.selectedIndex = ((($scope.pagination.currentPage - 1) * $scope.pagination.pageSize) + i);
				$scope.selectedUser = user;
			}
		};

		$scope.cancelEdit = function() {
			$scope.selectedIndex = null;
			$scope.selectedUser = null;

			$scope.users = UserService.query(null, {
				success: function(users) {
					$scope.search.applyFilter(false);
				}
			});
		};

		$scope.saveUser = function () {
			UserService.update($scope.selectedUser, {
				success: function(r) {
					if ($scope.selectedIndex >= 0) {
						$scope.users[$scope.selectedIndex] = r;
						$scope.search.applyFilter(false);
					} else {
						
					}
				},
				error: function(r) {

				}
			});
		};
	}
]);
