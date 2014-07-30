launch.module.controller('MeasureController', [
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

		self.init = function() {
			self.loggedInUser = authService.userInfo();

			$scope.selectedTab = 'overview';
			$scope.selectedTab = 'creation-stats';

			$scope.contentTypes = contentService.getContentTypes(self.ajaxHandler);
			$scope.campaigns = campaignService.query(self.loggedInUser.account.id, null, self.ajaxHandler);
			$scope.users = userService.getForAccount(self.loggedInUser.account.id, null, self.ajaxHandler);

			$scope.selectTab($scope.selectedTab);

			$scope.overview = measureService.getOverview(self.loggedInUser.account.id, self.ajaxHandler);
		};

		$scope.overview = null;
		$scope.contentTypes = null;
		$scope.campaigns = null;
		$scope.users = null;
		$scope.content = null;
		$scope.filteredContent = null;
		$scope.pagedContent = null;

		$scope.selectedTab = null;
		$scope.isMeasure = true;
		$scope.isLoading = false;
		$scope.isOverview = false;

		$scope.formatContentTypeItem = launch.utils.formatContentTypeItem;
		$scope.formatCampaignItem = launch.utils.formatCampaignItem;
		$scope.formatContentTypeIcon = launch.utils.getContentTypeIconClass;
		$scope.formatDate = launch.utils.formatDate;

		$scope.pagination = {
			totalItems: 0,
			currentSort: 'title',
			currentSortDirection: null,
			pageSize: 5,
			currentPage: 1,
			maxPage: 0,
			onPageChange: function (page) {
			},
			groupToPages: function () {
				$scope.pagedContent = [];

				for (var i = 0; i < $scope.filteredContent.length; i++) {
					if (i % $scope.pagination.pageSize === 0) {
						$scope.pagedContent[Math.floor(i / $scope.pagination.pageSize)] = [$scope.filteredContent[i]];
					} else {
						$scope.pagedContent[Math.floor(i / $scope.pagination.pageSize)].push($scope.filteredContent[i]);
					}
				}

				$scope.pagination.maxPage = $scope.pagedContent.length;
			},
			getPageIndicator: function () {
				var start = ((($scope.pagination.currentPage - 1) * $scope.pagination.pageSize) + 1);
				var end = ($scope.pagination.currentPage * $scope.pagination.pageSize);

				if (end > $scope.pagination.totalItems) {
					end = $scope.pagination.totalItems;
				}

				return start + ' to ' + end + ' of ' + $scope.pagination.totalItems;
			},
			reset: function (sort, direction) {
				if (!launch.utils.isBlank(sort)) {
					$scope.pagination.currentSort = sort;
				}

				if (!launch.utils.isBlank(direction)) {
					$scope.pagination.currentSortDirection = (direction === 'desc' ? 'desc' : 'asc');
				}

				$scope.pagination.currentPage = 1;
			}
		};

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

				if ($scope.isOverview && $scope.filteredContent.length > 4) {
					$scope.filteredContent.splice(4, $scope.filteredContent.length);
				}

				if (reset === true) {
					$scope.pagination.reset();
				}

				$scope.pagination.totalItems = $scope.filteredContent.length;
				$scope.pagination.groupToPages();
			},
			clearFilter: function () {
				$scope.search.searchTerm = null;
				$scope.search.contentTypes = [];
				$scope.search.campaigns = [];
				$scope.search.users = [];

				$scope.search.applyFilter(true);
			},
			toggleMyTasks: function (mine) {
				$scope.search.myTasks = !!mine;

				$scope.search.applyFilter(true);
			}
		};

		$scope.formatUserItem = function (item, element, context) {
			var user = $.grep($scope.users, function (u, i) { return u.id === parseInt(item.id); });
			var style = (user.length === 1 && !launch.utils.isBlank(user[0].image)) ? ' style="background-image: ' + user[0].imageUrl() + '"' : '';

			return '<span class="user-image user-image-small"' + style + '></span> <span>' + item.text + '</span>';
		};

		$scope.selectTab = function (tab) {
			$scope.isLoading = true;
			$scope.isOverview = false;

			switch (tab) {
				case 'creation-stats':
					console.log('LOAD CREATION STATS...');
					$scope.selectedTab = tab;
					$scope.isLoading = false;
					break;
				case 'content-trends':
					console.log('LOAD CONTENT TRENDS...');
					$scope.selectedTab = tab;
					$scope.isLoading = false;
					break;
				case 'marketing-automation':
					console.log('LOAD MARKETING AUTOMATION...');
					$scope.selectedTab = tab;
					$scope.isLoading = false;
					break;
				default:
					console.log('LOAD OVERVIEW STATS...');
					$scope.isOverview = true;
					$scope.content = contentService.query(self.loggedInUser.account.id, null, {
						success: function (r) {
							if (tab === 'content-details') {
								$scope.applySort('title');
								$scope.search.applyFilter(true);
							} else {
								$scope.pagination.currentSortDirection = 'desc';
								$scope.applySort('contentscore');
								$scope.search.applyFilter(false);
							}

							$scope.selectedTab = tab;
							$scope.isLoading = false;
						},
						error: self.ajaxHandler.error
					});
					break;
			}
		};

		$scope.saveFilter = function () {
			var page = 'measure';

			userService.savePreferences(self.loggedInUser.id, page, $scope.search, {
				success: function (r) {
					notificationService.success('Success!', launch.utils.titleCase(page) + ' default filters saved!');
					self.loggedInUser = authService.fetchCurrentUser(self.ajaxHandler);
				},
				error: self.ajaxHandler.error
			});
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

			$scope.content.sort(function (a, b) {
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
			});

			$scope.search.applyFilter(false);
		};

		$scope.viewInPromote = function(content, e) {
			if (!content) {
				return;
			}

			$location.path('/promote/content/' + content.id);
			e.stopImmediatePropagation();
		};

		$scope.editContent = function(content, e) {
			if (!content) {
				return;
			}

			$location.path('/measure/content/' + content.id);
			e.stopImmediatePropagation();
		};

		self.init();
	}
]);