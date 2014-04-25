launch.module.controller('UsersController', [
	'$scope', '$location', '$filter', '$modal', 'AuthService', 'UserService', 'RoleService', 'NotificationService', 'SessionService', function ($scope, $location, $filter, $modal, authService, userService, roleService, notificationService, sessionService) {
		var self = this;

		self.loggedInUser = null;

		self.init = function() {
			self.loggedInUser = authService.userInfo();
			self.loadUsers(true);

			$scope.showNewUser = self.loggedInUser.hasPrivilege('settings_execute_users');
			$scope.showUsers = self.loggedInUser.hasPrivilege(['settings_view_profiles', 'settings_edit_profiles']);
		};

		self.loadUsers = function(reset, cb) {
			var callback = {
				success: function(users) {
					$scope.isLoading = false;
					$scope.search.applyFilter(reset);

					if (!!cb && $.isFunction(cb.success)) {
						cb.success(users);
					}
				},
				error: function(r) {
					$scope.isLoading = false;

					if (!!cb && $.isFunction(cb.error)) {
						cb.error(r);
					}
				}
			};

			$scope.isLoading = true;

			if (self.loggedInUser.role.isGlobalAdmin === true) {
				$scope.users = userService.getByRole(self.loggedInUser.roles, callback);
			} else {
				$scope.users = userService.getForAccount(self.loggedInUser.account.id, callback);
			}
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
		$scope.showNewUser = false;
		$scope.showUsers = false;

		$scope.selfEditing = function() {
			if (!!$scope.selectedUser) {
				return $scope.selectedUser.id === self.loggedInUser.id;
			}

			return false;
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
					if ($scope.search.userStatus === 'all' || ($scope.search.userStatus === 'active' && user.active) || $scope.search.userStatus === 'inactive' && !user.active) {
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
					this.currentPage = (!!$scope.selectedUser) ? 1 : page;
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

		$scope.enterNewUser = function() {
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
			self.loadUsers(true, {
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

		$scope.afterSaveSuccess = function (user, form) {
			$scope.selectedUser = user;

			if ($scope.selfEditing()) {
				sessionService.set(sessionService.USER_KEY, user);
			}

			self.loadUsers(false, {
				success: function () {
					self.adjustPage(user.id, form);
				}
			});
		};

		self.init();
	}
]);