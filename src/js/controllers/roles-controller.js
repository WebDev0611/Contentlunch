launch.module.controller('RolesController', [
	'$scope', '$filter', 'RoleService', 'AuthService', function ($scope, $filter, roleService, authService) {
		var self = this;

		self.loggedInUser = null;

		self.init = function () {
			self.loggedInUser = authService.userInfo();

			self.accountId = self.loggedInUser.account.id;
			self.loadRoles(true);

			$scope.showNewRole = self.loggedInUser.hasPrivilege('settings_execute_roles');
			$scope.showRoles = self.loggedInUser.hasPrivilege(['settings_view_roles', 'settings_edit_roles']);
		};

		self.loadRoles = function (reset, callback) {
			$scope.isLoading = true;

			$scope.roles = roleService.query(self.accountId, {
				success: function (roles) {
					$scope.isLoading = false;
					$scope.search.applyFilter(reset);

					if (!!callback && $.isFunction(callback.success)) {
						callback.success(roles);
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

		self.reset = function (form) {
			$scope.selectedIndex = null;
			$scope.selectedRole = null;

			if (!!form) {
				form.$setPristine();
			}
		};

		self.adjustPage = function (roleId, form) {
			if (!$scope.selectedRole) {
				return;
			}

			var index = null;
			var role = $.grep($scope.roles, function (a, i) {
				if (roleId === a.id) {
					index = i;
					return true;
				}

				return false;
			});

			if (role.length === 1) {
				$scope.pagination.currentPage = parseInt(index / $scope.pagination.pageSize) + 1;

				if ($scope.selectedRole.id !== role[0].id) {
					$scope.selectRole(role[0], index, form);
				}
			}
		};

		self.roleExists = function (role, roles) {
			if (!role || !$.isArray(roles) || roles.length === 0) {
				return false;
			}

			for (var i = 0; i < roles.length; i++) {
				if (role.id === roles[i].id) {
					return true;
				}
			}

			return false;
		};

		$scope.roles = [];
		$scope.filteredRoles = [];
		$scope.pagedRoles = [];
		$scope.isLoading = false;
		$scope.isSaving = false;
		$scope.selectedIndex = null;
		$scope.selectedRole = null;
		$scope.showNewRole = false;
		$scope.showRoles = false;

		$scope.search = {
			searchTerm: null,
			searchTermMinLength: 1,
			roleStatus: 'active',
			toggleStatus: function (status) {
				this.roleStatus = status;
				this.applyFilter(true);
			},
			applyFilter: function (reset) {
				$scope.filteredRoles = $filter('filter')($scope.roles, function (role) {
					if ($scope.search.roleStatus === 'all' || ($scope.search.roleStatus === 'active' && role.active) || ($scope.search.roleStatus === 'inactive' && !role.active)) {
						if (!launch.utils.isBlank($scope.search.searchTerm) && $scope.search.searchTerm.length >= $scope.search.searchTermMinLength) {
							return (launch.utils.isBlank($scope.search.searchTerm) ? true : role.matchSearchTerm($scope.search.searchTerm));
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

				$scope.pagination.totalItems = $scope.filteredRoles.length;
				$scope.pagination.groupToPages();

				if (!!$scope.selectedRole) {
					if (self.roleExists($scope.selectedRole, $scope.filteredRoles)) {
						self.adjustPage($scope.selectedRole.id, null);
					} else {
						self.reset();
					}
				}
			}
		};

		$scope.pagination = {
			totalItems: 0,
			pageSize: 10,
			currentPage: 1,
			currentSort: 'roleName',
			currentSortDirection: 'ASC',
			onPageChange: function (page, form) {
				if (!self.roleExists($scope.selectedRole, $scope.pagedRoles[page - 1])) {
					this.currentPage = (!!$scope.selectedRole) ? 1 : page;
					$scope.selectRole(null, null, form);
				}

				// IF WE WANT TO PAGE FROM THE SERVER, ENTER THAT CODE AND
				// REMOVE THE getPagedRoles FUNCTION BELOW. ALSO, WE'LL NEED
				// TO TWEAK THE WHAT THAT pagination.totalItems IS CALCULATED
				// SUCH THAT THIS VALUE COMES BACK IN THE JSON RESPONSE.
			},
			showPager: function () {
				return (this.totalItems > this.pageSize);
			},
			groupToPages: function () {
				$scope.pagedRoles = [];

				for (var i = 0; i < $scope.filteredRoles.length; i++) {
					if (i % $scope.pagination.pageSize === 0) {
						$scope.pagedRoles[Math.floor(i / $scope.pagination.pageSize)] = [$scope.filteredRoles[i]];
					} else {
						$scope.pagedRoles[Math.floor(i / $scope.pagination.pageSize)].push($scope.filteredRoles[i]);
					}
				}
			}
		};

		$scope.isSelectedRole = function (role) {
			if (!$scope.selectedRole || !role) {
				return false;
			}

			return (role.id === $scope.selectedRole.id);
		};

		$scope.enterNewRole = function () {
			$scope.selectedIndex = -1;
			$scope.selectedRole = roleService.getNewRole(self.accountId);
		};

		$scope.selectRole = function (role, i, form) {
			if (!role || (!!$scope.selectedRole && $scope.selectedRole.id === role.id)) {
				$scope.selectedRole = null;
				self.reset(form);
			} else {
				$scope.selectedIndex = ((($scope.pagination.currentPage - 1) * $scope.pagination.pageSize) + i);
				$scope.selectedRole = role;
			}
		};

		$scope.refreshMethod = function (form) {
			var roleId = $scope.selectedRole.id;

			self.reset(form);
			self.loadRoles(true, {
				success: function () {
					var index = null;
					var role = $.grep($scope.roles, function (u, i) {
						if (u.id === roleId) {
							index = i;
							return true;
						}

						return false;
					});

					if (role.length === 1) {
						$scope.selectRole(role[0], index, form);
						self.adjustPage(roleId, form);
					}
				}
			});
		};

		$scope.afterSaveSuccess = function (r, form) {
			self.loadRoles(false, {
				success: function () {
					if (!!r.id) {
						$scope.selectedRole = roleService.get(r.id, self.accountId);
						self.adjustPage(r.id, form);
					} else {
						$scope.selectedRole = null;
					}
				}
			});
		};

		self.init();
	}
]);