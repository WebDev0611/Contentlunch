launch.module.controller('AccountsController', [
	'$scope', '$filter', 'AuthService', 'AccountService', 'NotificationService', function ($scope, $filter, authService, accountService, notificationService) {
		var self = this;

		self.loggedInUser = null;

		self.init = function() {
			self.loadAccounts(true);

			self.loggedInUser = authService.userInfo();
		};

		self.loadAccounts = function (reset, callback) {
			$scope.isLoading = true;
			$scope.renderPager = false;

			$scope.accounts = accountService.query({
				success: function(accounts) {
					$scope.isLoading = false;
					$scope.search.applyFilter(reset);
					$scope.renderPager = true;

					if (!!callback && $.isFunction(callback.success)) {
						callback.success(accounts);
					}
				},
				error: function(r) {
					$scope.isLoading = false;

					if (!!callback && $.isFunction(callback.error)) {
						callback.error(r);
					}
				}
			});
		};

		self.reset = function (form) {
			$scope.selectedIndex = null;
			$scope.selectedAccount = null;

			if (!!form) {
				form.$setPristine();
			}
		};

		self.adjustPage = function (accountId, form) {
			if (!$scope.selectedAccount) {
				return;
			}

			var index = null;
			var account = $.grep($scope.accounts, function (a, i) {
				if (accountId === a.id) {
					index = i;
					return true;
				}

				return false;
			});

			if (account.length === 1) {
				$scope.pagination.currentPage = parseInt(index / $scope.pagination.pageSize) + 1;

				if ($scope.selectedAccount.id !== account[0].id) {
					$scope.selectAccount(account[0], index, form);
				}
			}
		};

		self.accountExists = function (account, accounts) {
			if (!account || !$.isArray(accounts) || accounts.length === 0) {
				return false;
			}

			for (var i = 0; i < accounts.length; i++) {
				if (account.id === accounts[i].id) {
					return true;
				}
			}

			return false;
		};

		$scope.accounts = [];
		$scope.filteredAccounts = [];
		$scope.pagedAccounts = [];
		$scope.isLoading = false;
		$scope.isSaving = false;
		$scope.renderPager = false;
		$scope.selectedIndex = null;
		$scope.selectedAccount = null;

		$scope.selfEditing = function () {
			if (!!$scope.selectedAccount && $.isArray(self.loggedInUser.accounts)) {
				return $.grep(self.loggedInUser.accounts, function(a, i) {
					return a.id === $scope.selectedAccount.id;
				}).length > 0;
			}

			return false;
		};

		$scope.search = {
			searchTerm: null,
			searchTermMinLength: 1,
			accountStatus: 'active',
			toggleStatus: function(status) {
				this.accountStatus = status;
				this.applyFilter(true);
			},
			applyFilter: function(reset) {
				var filteredResults = $filter('filter')($scope.accounts, function(account) {
					if ($scope.search.accountStatus === 'all' || ($scope.search.accountStatus === 'active' && account.active) || ($scope.search.accountStatus === 'inactive' && !account.active)) {
						if (!launch.utils.isBlank($scope.search.searchTerm) && $scope.search.searchTerm.length >= $scope.search.searchTermMinLength) {
							return (launch.utils.isBlank($scope.search.searchTerm) ? true : account.matchSearchTerm($scope.search.searchTerm));
						} else {
							return true;
						}
					} else {
						return false;
					}
				});

				var sortedAccounts = [];

				// Give account/company name priority in the results list
				// if the user is searching
				if (!launch.utils.isBlank($scope.search.searchTerm)) {
					var filteredResultsLength = filteredResults.length;
					for (var i = filteredResultsLength - 1; i >= 0; i--) {
						if (launch.utils.startsWith(filteredResults[i].name, $scope.search.searchTerm)) {
							sortedAccounts.push(filteredResults[i]);
							filteredResults.splice(i, 1);
						}
					}
				}

				$scope.filteredAccounts = sortedAccounts.concat(filteredResults);

				if (reset === true) {
					$scope.pagination.currentPage = 1;
				}

				$scope.pagination.totalItems = $scope.filteredAccounts.length;
				$scope.pagination.groupToPages();

				if (!!$scope.selectedAccount) {
					if (self.accountExists($scope.selectedAccount, $scope.filteredAccounts)) {
						self.adjustPage($scope.selectedAccount.id, null);
					} else {
						self.reset();
					}
				}
			}
		};

		$scope.pagination = {
			totalItems: 0,
			pageSize: 5,
			currentPage: 1,
			currentSort: 'firstName',
			currentSortDirection: 'ASC',
			onPageChange: function (page, form) {
				if (!self.accountExists($scope.selectedAccount, $scope.pagedAccounts[page - 1])) {
					this.currentPage = (!!$scope.selectedAccount) ? 1 : page;
					$scope.selectAccount(null, null, form);
				}

				// IF WE WANT TO PAGE FROM THE SERVER, ENTER THAT CODE AND
				// REMOVE THE getPagedAccounts FUNCTION BELOW. ALSO, WE'LL NEED
				// TO TWEAK THE WHAT THAT pagination.totalItems IS CALCULATED
				// SUCH THAT THIS VALUE COMES BACK IN THE JSON RESPONSE.
			},
			showPager: function () {
				return (this.totalItems > this.pageSize);
			},
			groupToPages: function () {
				$scope.pagedAccounts = [];

				for (var i = 0; i < $scope.filteredAccounts.length; i++) {
					if (i % $scope.pagination.pageSize === 0) {
						$scope.pagedAccounts[Math.floor(i / $scope.pagination.pageSize)] = [$scope.filteredAccounts[i]];
					} else {
						$scope.pagedAccounts[Math.floor(i / $scope.pagination.pageSize)].push($scope.filteredAccounts[i]);
					}
				}
			}
		};

		$scope.isSelectedAccount = function (account) {
			if (!$scope.selectedAccount || !account) {
				return false;
			}

			return (account.id === $scope.selectedAccount.id);
		};

		$scope.enterNewAccount = function () {
			$scope.selectedIndex = -1;
			$scope.selectedAccount = accountService.getNewAccount();
		};

		$scope.selectAccount = function (account, i, form) {
			if (!account || (!!$scope.selectedAccount && $scope.selectedAccount.id === account.id)) {
				$scope.selectedAccount = null;
				self.reset(form);
			} else {
				$scope.selectedIndex = ((($scope.pagination.currentPage - 1) * $scope.pagination.pageSize) + i);
				$scope.selectedAccount = account;
			}
		};

		$scope.refreshMethod = function (form, selectedAccount) {
			var accountId = (!!selectedAccount) ? selectedAccount.id : null;

			self.reset(form);
			self.loadAccounts(true, {
				success: function () {
					var index = null;
					var account = $.grep($scope.accounts, function (u, i) {
						if (u.id === accountId) {
							index = i;
							return true;
						}

						return false;
					});

					if (account.length === 1) {
						$scope.selectAccount(account[0], index, form);
						self.adjustPage(accountId, form);
					}
				}
			});
		};

		$scope.afterSaveSuccess = function (r, form) {
			self.loadAccounts(false, {
				success: function () {
					$scope.selectedAccount = accountService.get(r.id);
					self.adjustPage(r.id, form);
				}
			});
		};

		self.init();
	}
]);