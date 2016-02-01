launch.module.controller('MeasureMarketingAutomationController', [
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

			$scope.paginationLandingPages = new launch.Pagination('title', 'asc');
			$scope.paginationBlogs = new launch.Pagination('title', 'asc');
			$scope.paginationEmails = new launch.Pagination('title', 'asc');
			$scope.applySortLandingPages = function (sort, direction) { };
			$scope.applySortBlogs = function (sort, direction) { };
			$scope.applySortEmails = function (sort, direction) { };

			$scope.isLoading = true;

			$scope.content = measureService.getAutomation(self.loggedInUser.account.id, {
				success: function (r) {
					console.log(r);
					$scope.isLoading = false;

					$scope.landingPages = $.grep($scope.content, function (c) { return c.contentType.baseType === 'long_html'; });
					$scope.blogs = $.grep($scope.content, function (c) { return c.contentType.baseType === 'blog_post'; });
					$scope.emails = $.grep($scope.content, function (c) { return c.contentType.baseType === 'email'; });

					$scope.landingPagesPaged = $scope.paginationLandingPages.groupToPages($scope.landingPages);
					$scope.blogsPaged = $scope.paginationBlogs.groupToPages($scope.blogs);
					$scope.emailsPaged = $scope.paginationEmails.groupToPages($scope.emails);
				},
                error: self.ajaxHandler.error
			});

			$scope.selectedTab = 'marketing-automation';
		};

		self.contentSort = function (a, b) {
			if (!a && !b) { return 0; }
			if (!!a && !b) { return (self.sortDirection === 'asc' ? -1 : 1); }
			if (!a && !!b) { return (self.sortDirection === 'asc' ? 1 : -1); }

			if (a.id === b.id) {
				return 0;
			}

			if (self.sort === 'title') {
				if (launch.utils.isBlank(a.title) && launch.utils.isBlank(b.title)) { return 0; }
				if (!launch.utils.isBlank(a.title) && launch.utils.isBlank(b.title)) { return (self.sortDirection === 'asc' ? -1 : 1); }
				if (launch.utils.isBlank(a.title) && !launch.utils.isBlank(b.title)) { return (self.sortDirection === 'asc' ? 1 : -1); }

				if (a.title.toLowerCase() === b.title.toLowerCase()) {
					return 0;
				}

				if (self.sortDirection === 'asc') {
					return (a.title.toLowerCase() < b.title.toLowerCase()) ? -1 : 1;
				} else {
					return (a.title.toLowerCase() > b.title.toLowerCase()) ? -1 : 1;
				}
			}

			if (self.sort === 'author') {
				if (!a.author && !b.author) { return 0; }
				if (!!a.author && !b.author) { return (self.sortDirection === 'asc' ? -1 : 1); }
				if (!a.author && !!b.author) { return (self.sortDirection === 'asc' ? 1 : -1); }

				if (a.author.id === b.author.id) {
					return 0;
				}

				if (a.author.formatName().toLowerCase() === b.author.formatName().toLowerCase()) {
					return (a.author.id < b.author.id) ? -1 : 1;
				}

				if (self.sortDirection === 'asc') {
					return (a.author.formatName().toLowerCase() < b.author.formatName().toLowerCase()) ? -1 : 1;
				} else {
					return (a.author.formatName().toLowerCase() > b.author.formatName().toLowerCase()) ? -1 : 1;
				}
			}

			if (self.sort === 'views') {
				if (isNaN(a.contentViews) && isNaN(b.contentViews)) { return 0; }
				if (!isNaN(a.contentViews) && isNaN(b.contentViews)) { return (self.sortDirection === 'asc' ? -1 : 1); }
				if (isNaN(a.contentViews) && !isNaN(b.contentViews)) { return (self.sortDirection === 'asc' ? 1 : -1); }

				var aViews = parseFloat(a.contentViews);
				var bViews = parseFloat(b.contentViews);

				if (aViews === bViews) {
					return 0;
				}

				if (self.sortDirection === 'asc') {
					return (aViews < bViews) ? -1 : 1;
				} else {
					return (aViews > bViews) ? -1 : 1;
				}
			}

			if (self.sort === 'conversionrate') {
				if (isNaN(a.contentConversionRate) && isNaN(b.contentConversionRate)) { return 0; }
				if (!isNaN(a.contentConversionRate) && isNaN(b.contentConversionRate)) { return (self.sortDirection === 'asc' ? -1 : 1); }
				if (isNaN(a.contentConversionRate) && !isNaN(b.contentConversionRate)) { return (self.sortDirection === 'asc' ? 1 : -1); }

				var aRate = parseFloat(a.contentConversionRate);
				var bRate = parseFloat(b.contentConversionRate);

				if (aRate === bViews) {
					return 0;
				}

				if (self.sortDirection === 'asc') {
					return (aRate < bRate) ? -1 : 1;
				} else {
					return (aRate > bRate) ? -1 : 1;
				}
			}

			return 0;
		};

		self.sort = null;
		self.sortDirection = null;

		$scope.content = null;
		$scope.landingPages = null;
		$scope.blogs = null;
		$scope.emails = null;
		$scope.landingPagesPaged = null;
		$scope.blogsPaged = null;
		$scope.emailsPaged = null;

		$scope.paginationLandingPages = null;
		$scope.paginationBlogs = null;
		$scope.paginationEmails = null;

		$scope.isMeasure = true;
		$scope.isLoading = false;
		$scope.isOverview = false;

		$scope.formatContentTypeItem = launch.utils.formatContentTypeItem;
		$scope.formatCampaignItem = launch.utils.formatCampaignItem;
		$scope.formatContentTypeIcon = launch.utils.getContentTypeIconClass;
		$scope.formatDate = launch.utils.formatDate;

		$scope.formatUserItem = function (item, element, context) {
			var user = $.grep($scope.users, function (u, i) { return u.id === parseInt(item.id); });
			var style = (user.length === 1 && !launch.utils.isBlank(user[0].image)) ? ' style="background-image: ' + user[0].imageUrl() + '"' : '';

			return '<span class="user-image user-image-small"' + style + '></span> <span>' + item.text + '</span>';
		};

		$scope.applySort = function (sort, type) {
			var items = null;
			var pagination = null;

			switch (type) {
				case 'landingpages':
					items = $scope.landingPages;
					pagination = $scope.paginationLandingPages;
					break;
				case 'blogs':
					items = $scope.blogs;
					pagination = $scope.paginationBlogs;
					break;
				case 'emails':
					items = $scope.emails;
					pagination = $scope.paginationEmails;
					break;
				default:
					return;
			}

			if (!$.isArray(items) || items.length === 0) {
				return;
			}

			if (launch.utils.isBlank(sort)) {
				sort = launch.utils.isBlank(pagination.currentSort) ? 'title' : pagination.currentSort;
			} else {
				sort = sort.toLowerCase();
			}

			pagination.reset();

			if (pagination.currentSort === sort) {
				pagination.currentSortDirection = (pagination.currentSortDirection === 'asc' ? 'desc' : 'asc');
			} else {
				pagination.currentSort = sort;
				pagination.currentSortDirection = 'asc';
			}

			self.sort = pagination.currentSort;
			self.sortDirection = pagination.currentSortDirection;

			items.sort(self.contentSort);

			self.sort = null;
			self.sortDirection = null;

			switch (type) {
				case 'landingpages':
					$scope.landingPagesPaged = pagination.groupToPages(items);
					break;
				case 'blogs':
					$scope.blogsPaged = pagination.groupToPages(items);
					break;
				case 'emails':
					$scope.emailsPaged = pagination.groupToPages(items);
					break;
			}
		};

		self.init();
	}
]);