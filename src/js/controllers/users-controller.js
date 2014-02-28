launch.module.controller('UsersController', [
	'$scope', '$location', 'UserService', function($scope, $location, UserService) {
		$scope.title = 'This is the users page controller';
		$scope.users = [];

		$scope.search = {
			searchTerm: null,
			userStatus: 'all',
			toggleStatus: function(status) {
				this.userStatus = status;
			}
		};

		$scope.pagination = {
			totalItems: 0,
			pageSize: 3,
			currentPage: 1,
			currentSort: null,
			currentSortDirection: 'ASC',
			onPageChange: function(page) {
				// IF WE WANT TO PAGE FROM THE SERVER, ENTER THAT CODE AND
				// REMOVE THE getPagedUsers FUNCTION BELOW. ALSO, WE'LL NEED
				// TO TWEAK THE WHAT THAT pagination.totalItems IS CALCULATED
				// SUCH THAT THIS VALUE COMES BACK IN THE JSON RESPONSE.
			},
			showPager: function() {
				return (this.totalItems > this.pageSize);
			}
		};

		$scope.users = UserService.query(null, {
			success: function(users) {
				$scope.pagination.totalItems = ($.isArray(users)) ? users.length : 0;
			}
		});

		$scope.getPagedUsers = function() {
			if (!$scope.pagination.showPager()) {
				return $scope.users;
			}

			var index = (($scope.pagination.currentPage - 1) * $scope.pagination.pageSize);
			var length = ($scope.pagination.pageSize * $scope.pagination.currentPage);
			var newArray = $.grep($scope.users, function(user, i) {
				return (i >= index && i < length);
			});

			return newArray;
		};
	}
]);
