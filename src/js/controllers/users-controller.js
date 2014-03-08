launch.module.controller('UsersController', [
	'$scope', '$location', '$filter', '$modal', 'AuthService', 'UserService', 'RoleService', 'NotificationService', function ($scope, $location, $filter, $modal, authService, userService, roleService, notificationService) {
		var self = this;

		self.loggedInUser = null;

		self.init = function() {
			$scope.loadUsers(true);

			self.loggedInUser = authService.userInfo();
		};

		self.reset = function(form) {
			$scope.selectedIndex = null;
			$scope.selectedUser = null;

			if (!!form) {
				form.$setPristine();
			}
		};

		self.adjustPage = function (userId, form) {
			if (!$scope.selectedUser) {
				return;
			}

			var index = null;
			var user = $.grep($scope.users, function(u, i) {
				if (userId === u.id) {
					index = i;
					return true;
				}

				return false;
			});

			if (user.length === 1) {
				$scope.pagination.currentPage = parseInt(index / $scope.pagination.pageSize) + 1;

				if ($scope.selectedUser.id !== user[0].id) {
					$scope.selectUser(user[0], index, form);
				}
			}
		};

		self.userExists = function(user, users) {
			if (!user || !$.isArray(users) || users.length === 0) {
				return false;
			}

			for (var i = 0; i < users.length; i++) {
				if (user.id === users[i].id) {
					return true;
				}
			}

			return false;
		};

		$scope.users = [];
		$scope.filteredUsers = [];
		$scope.pagedUsers = [];
		$scope.isLoading = false;
		$scope.isSaving = false;
		$scope.selectedIndex = null;
		$scope.selectedUser = null;

		$scope.selfEditing = function() {
			if (!!$scope.selectedUser) {
				return $scope.selectedUser.id === self.loggedInUser.id;
			}

			return false;
		};

		$scope.loadUsers = function (reset, callback) {
			$scope.isLoading = true;

			$scope.users = userService.query(null, {
				success: function (users) {
					$scope.isLoading = false;
					$scope.search.applyFilter(reset);

					if (!!callback && $.isFunction(callback.success)) {
						callback.success(users);
					}
				},
				error: function (r) {
					$scope.isLoading = false;

					if (!!callback && $.isFunction(callback.error)) {
						callback.error(r);
					}
				}
			});
		};

		$scope.search = {
			searchTerm: null,
			searchTermMinLength: 1,
			userStatus: 'active',
			toggleStatus: function(status) {
				this.userStatus = status;
				this.applyFilter(true);
			},
			applyFilter: function(reset) {
				$scope.filteredUsers = $filter('filter')($scope.users, function(user) {
					if ($scope.search.userStatus === 'all' || $scope.search.userStatus === user.active) {
						if (!launch.utils.isBlank($scope.search.searchTerm) && $scope.search.searchTerm.length >= $scope.search.searchTermMinLength) {
							return (launch.utils.isBlank($scope.search.searchTerm) ? true : user.matchSearchTerm($scope.search.searchTerm));
						} else {
							return true;
						}
					} else {
						return false;
					}
				});

				if (reset === true) {
					$scope.pagination.currentPage = 1;
				}

				$scope.pagination.totalItems = $scope.filteredUsers.length;
				$scope.pagination.groupToPages();

				if (!!$scope.selectedUser) {
					if (self.userExists($scope.selectedUser, $scope.filteredUsers)) {
						self.adjustPage($scope.selectedUser.id, null);
					} else {
						self.reset();
					}
				}
			}
		};

		$scope.pagination = {
			totalItems: 0,
			pageSize: 3,
			currentPage: 1,
			currentSort: 'firstName',
			currentSortDirection: 'ASC',
			onPageChange: function(page, form) {
				if (!self.userExists($scope.selectedUser, $scope.pagedUsers[page - 1])) {
					this.currentPage = 1;
					$scope.selectUser(null, null, form);
				}

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

		$scope.isSelectedUser = function(user) {
			if (!$scope.selectedUser || !user) {
				return false;
			}

			return (user.id === $scope.selectedUser.id);
		};

		$scope.enterNewUser = function(form) {
			//form.$setPristine();
			$scope.selectedIndex = -1;
			$scope.selectedUser = userService.getNewUser();
		};

		$scope.selectUser = function(user, i, form) {
			if (!user || $scope.selectedUser === user) {
				self.reset(form);
			} else {
				$scope.selectedIndex = ((($scope.pagination.currentPage - 1) * $scope.pagination.pageSize) + i);
				$scope.selectedUser = user;
			}
		};

		$scope.refreshMethod = function(form) {
			var userId = $scope.selectedUser.id;

			self.reset(form);
			$scope.loadUsers(true, {
				success: function() {
					var index = null;
					var user = $.grep($scope.users, function(u, i) {
						if (u.id === userId) {
							index = i;
							return true;
						}

						return false;
					});

					if (user.length === 1) {
						$scope.selectUser(user[0], index, form);
						self.adjustPage(userId, form);
					}
				}
			});
		};

		$scope.afterSaveSuccess = function(r, form) {
			$scope.loadUsers(false, {
				success: function () {
					self.adjustPage(r.id, form);
				}
			});
		};

		self.init();
	}
]);