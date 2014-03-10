launch.module.controller('AccountsController', [
	'$scope', '$filter', 'AccountService', function ($scope, $filter, accountService) {
		var self = this;

		self.init = function() {
			self.loadAccounts(true);
		};

		self.loadAccounts = function (reset, callback) {
			$scope.isLoading = true;

			$scope.accounts = accountService.query(null, {
				success: function (accounts) {
					$scope.isLoading = false;
					$scope.search.applyFilter(reset);

					if (!!callback && $.isFunction(callback.success)) {
						callback.success(accounts);
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
		$scope.selectedIndex = null;
		$scope.selectedAccount = null;

		$scope.selfEditing = function () {
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
				$scope.filteredAccounts = $filter('filter')($scope.accounts, function(account) {
					if ($scope.search.accountStatus === 'all' || $scope.search.accountStatus === account.active) {
						if (!launch.utils.isBlank($scope.search.searchTerm) && $scope.search.searchTerm.length >= $scope.search.searchTermMinLength) {
							return (launch.utils.isBlank($scope.search.searchTerm) ? true : account.matchSearchTerm($scope.search.searchTerm));
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
			pageSize: 3,
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
			if (!account || $scope.selectedAccount === account) {
				self.reset(form);
			} else {
				$scope.selectedIndex = ((($scope.pagination.currentPage - 1) * $scope.pagination.pageSize) + i);
				$scope.selectedAccount = account;
			}
		};

		$scope.refreshMethod = function (form) {
			var accountId = $scope.selectedAccount.id;

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
					self.adjustPage(r.id, form);
				}
			});
		};

		self.init();
	}
]);