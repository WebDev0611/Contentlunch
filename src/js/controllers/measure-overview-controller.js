launch.module.controller('MeasureOverviewController', [
	'$scope', '$location', '$filter', 'AuthService', 'UserService', 'ContentService', 'CampaignService', 'MeasureService', 'NotificationService', function ($scope, $location, $filter, authService, userService, contentService, campaignService, measureService, notificationService) {
		var self = this;

		self.loggedInUser = null;

		self.ajaxHandler = {
			success: function (r) {

			},
			error: function (r) {
				launch.utils.handleAjaxErrorResponse(r, notificationService);
			}
		};

		self.init = function () {
			self.loggedInUser = authService.userInfo();

			$scope.isLoading = true;

			$scope.content = contentService.query(self.loggedInUser.account.id, null, {
				success: function (r) {
					$scope.isLoading = false;
					$scope.pagination.currentSortDirection = 'asc';
					$scope.applySort('contentscore');
					$scope.search.applyFilter(false);
				},
				error: self.ajaxHandler.error
			});

			$scope.selectedTab = 'overview';

			$scope.overview = measureService.getOverview(self.loggedInUser.account.id, self.ajaxHandler);
		};

		self.contentSort = function (a, b) {
			if (!a && !b) { return 0; }
			if (!!a && !b) { return ($scope.pagination.currentSortDirection === 'asc' ? -1 : 1); }
			if (!a && !!b) { return ($scope.pagination.currentSortDirection === 'asc' ? 1 : -1); }

			if (a.id === b.id) {
				return 0;
			}

			if ($scope.pagination.currentSort === 'title') {
				if (launch.utils.isBlank(a.title) && launch.utils.isBlank(b.title)) { return 0; }
				if (!launch.utils.isBlank(a.title) && launch.utils.isBlank(b.title)) { return ($scope.pagination.currentSortDirection === 'asc' ? -1 : 1); }
				if (launch.utils.isBlank(a.title) && !launch.utils.isBlank(b.title)) { return ($scope.pagination.currentSortDirection === 'asc' ? 1 : -1); }

				if (a.title.toLowerCase() === b.title.toLowerCase()) {
					return 0;
				}

				if ($scope.pagination.currentSortDirection === 'asc') {
					return (a.title.toLowerCase() < b.title.toLowerCase()) ? -1 : 1;
				} else {
					return (a.title.toLowerCase() > b.title.toLowerCase()) ? -1 : 1;
				}
			}

			if ($scope.pagination.currentSort === 'author') {
				if (!a.author && !b.author) { return 0; }
				if (!!a.author && !b.author) { return ($scope.pagination.currentSortDirection === 'asc' ? -1 : 1); }
				if (!a.author && !!b.author) { return ($scope.pagination.currentSortDirection === 'asc' ? 1 : -1); }

				if (a.author.id === b.author.id) {
					return 0;
				}

				if (a.author.formatName().toLowerCase() === b.author.formatName().toLowerCase()) {
					return (a.author.id < b.author.id) ? -1 : 1;
				}

				if ($scope.pagination.currentSortDirection === 'asc') {
					return (a.author.formatName().toLowerCase() < b.author.formatName().toLowerCase()) ? -1 : 1;
				} else {
					return (a.author.formatName().toLowerCase() > b.author.formatName().toLowerCase()) ? -1 : 1;
				}
			}

			if ($scope.pagination.currentSort === 'launchdate') {
				if (!launch.utils.isValidDate(a.launchDate) && !launch.utils.isValidDate(b.launchDate)) { return 0; }
				if (!launch.utils.isValidDate(a.launchDate) && launch.utils.isValidDate(b.launchDate)) { return ($scope.pagination.currentSortDirection === 'asc' ? -1 : 1); }
				if (launch.utils.isValidDate(a.launchDate) && !launch.utils.isValidDate(b.launchDate)) { return ($scope.pagination.currentSortDirection === 'asc' ? 1 : -1); }

				var aLaunchDate = launch.utils.formatDate(a.launchDate);
				var bLaunchDate = launch.utils.formatDate(b.launchDate);

				if (aLaunchDate === bLaunchDate) {
					return 0;
				}

				if ($scope.pagination.currentSortDirection === 'asc') {
					return (aLaunchDate < bLaunchDate) ? -1 : 1;
				} else {
					return (aLaunchDate > bLaunchDate) ? -1 : 1;
				}
			}

			if ($scope.pagination.currentSort === 'promotedate') {
				if (!launch.utils.isValidDate(a.promoteDate) && !launch.utils.isValidDate(b.promoteDate)) { return 0; }
				if (!launch.utils.isValidDate(a.promoteDate) && launch.utils.isValidDate(b.promoteDate)) { return ($scope.pagination.currentSortDirection === 'asc' ? -1 : 1); }
				if (launch.utils.isValidDate(a.promoteDate) && !launch.utils.isValidDate(b.promoteDate)) { return ($scope.pagination.currentSortDirection === 'asc' ? 1 : -1); }

				var aPromoteDate = launch.utils.formatDate(a.promoteDate);
				var bPromoteDate = launch.utils.formatDate(b.promoteDate);

				if (aPromoteDate === bPromoteDate) {
					return 0;
				}

				if ($scope.pagination.currentSortDirection === 'asc') {
					return (aPromoteDate < bPromoteDate) ? -1 : 1;
				} else {
					return (aPromoteDate > bPromoteDate) ? -1 : 1;
				}
			}

			if ($scope.pagination.currentSort === 'contentscore') {
				if (isNaN(a.contentScore) && isNaN(b.contentScore)) { return 0; }
				if (!isNaN(a.contentScore) && isNaN(b.contentScore)) { return ($scope.pagination.currentSortDirection === 'asc' ? -1 : 1); }
				if (isNaN(a.contentScore) && !isNaN(b.contentScore)) { return ($scope.pagination.currentSortDirection === 'asc' ? 1 : -1); }

				var aScore = parseFloat(a.contentScore);
				var bScore = parseFloat(b.contentScore);

				if (aScore === bScore) {
					return 0;
				}

				if ($scope.pagination.currentSortDirection === 'asc') {
					return (aScore < bScore) ? -1 : 1;
				} else {
					return (aScore > bScore) ? -1 : 1;
				}
			}

			return 0;
		};

		$scope.overview = null;
		$scope.content = null;
		$scope.filteredContent = null;
		$scope.pagedContent = null;

		$scope.isMeasure = true;
		$scope.isOverview = true;
		$scope.isLoading = false;

		$scope.pagination = new launch.Pagination('contentscore', 'desc');

		$scope.search = {
			searchTerm: null,
			searchTermMinLength: 1,
			myTasks: false,
			contentTypes: [],
			buyingStages: [],
			campaigns: [],
			users: [],
			changeSearchTerm: function () {
				if (launch.utils.isBlank($scope.search.searchTerm) || $scope.search.searchTerm.length >= $scope.search.searchTermMinLength) {
					$scope.search.applyFilter(true);
				}
			},
			applyFilter: function (reset) {
				if (!$scope.content || !$scope.content.$resolved) {
					return;
				}

				$scope.filteredContent = $filter('filter')($scope.content, function (content) {
					if (content.status < 3 || content.archived) {
						return false;
					}

					if ($scope.search.myTasks && content.author.id !== self.loggedInUser.id) {
						return false;
					}

					if ($.isArray($scope.search.contentTypes) && $scope.search.contentTypes.length > 0) {
						if ($.inArray(content.contentType.name, $scope.search.contentTypes) < 0) {
							return false;
						}
					}

					if ($.isArray($scope.search.campaigns) && $scope.search.campaigns.length > 0) {
						var campaignId = launch.utils.isBlank(content.campaign.id) ? '' : content.campaign.id.toString();

						if ($.inArray(campaignId, $scope.search.campaigns) < 0) {
							return false;
						}
					}

					if ($.isArray($scope.search.users) && $scope.search.users.length > 0) {
						return ($.grep($scope.search.users, function (uid) { return parseInt(uid) === content.author.id; }).length > 0);
					}

					return (launch.utils.isBlank($scope.search.searchTerm) ? true : content.matchSearchTerm($scope.search.searchTerm));
				});

				if ($scope.filteredContent.length > 4) {
					$scope.filteredContent.splice(4, $scope.filteredContent.length);
				}

				if (reset === true) {
					$scope.pagination.reset();
				}

				$scope.pagination.totalItems = $scope.filteredContent.length;
				$scope.pagedContent = $scope.pagination.groupToPages($scope.filteredContent);
			},
			clearFilter: function () {
				$scope.search.searchTerm = null;
				$scope.search.contentTypes = [];
				$scope.search.campaigns = [];
				$scope.search.users = [];

				$scope.search.applyFilter(true);
			}
		};

		$scope.applySort = function (sort) {
			if (launch.utils.isBlank(sort)) {
				sort = launch.utils.isBlank($scope.pagination.currentSort) ? 'title' : $scope.pagination.currentSort;
			} else {
				sort = sort.toLowerCase();
			}

			$scope.pagination.reset();

			if ($scope.pagination.currentSort === sort) {
				$scope.pagination.currentSortDirection = ($scope.pagination.currentSortDirection === 'asc' ? 'desc' : 'asc');
			} else {
				$scope.pagination.currentSort = sort;
				$scope.pagination.currentSortDirection = ($scope.pagination.currentSortDirection === 'desc' ? 'desc' : 'asc');
			}

			if (!$.isArray($scope.content) || $scope.content.length === 0 || !$scope.content.$resolved) {
				return;
			}

			$scope.content.sort(self.contentSort);

			$scope.search.applyFilter(false);
		};

		self.init();
	}
]);