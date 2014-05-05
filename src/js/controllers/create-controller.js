launch.module.controller('CreateController', [
	'$scope', '$filter', '$location', 'AuthService', 'UserService', 'ContentSettingsService', 'ContentService', 'NotificationService', function ($scope, $filter, $location, authService, userService, contentSettingsService, contentService, notificationService) {
		var self = this;

		self.loggedInUser = null;

		self.init = function () {
			self.loggedInUser = authService.userInfo();

			$scope.milestones = [
				{ name: 'concept', title: 'Concept' },
				{ name: 'create', title: 'Created' },
				{ name: 'approve', title: 'Approved' },
				{ name: 'launch', title: 'Launched' },
				{ name: 'archive', title: 'Archived' }
			];

			$scope.contentTypes = contentService.getContentTypes({
				success: function(r) {
					
				},
				error: function(r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});

			var contentSettings = contentSettingsService.get(self.loggedInUser.account.id, {
				success: function(r) {
					$scope.buyingStages = $.map(contentSettings.personaProperties, function(bs, i) {
						return { name: bs, id: i + 1 };
					});
				},
				error: function(r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});

			//TODO: POPULATE CAMPAIGNS FROM API!!
			$scope.campaigns = null;
			$scope.users = userService.getForAccount(self.loggedInUser.account.id);

			$scope.content = contentService.query(self.loggedInUser.account.id, null, {
				success: function(r) {
					$scope.search.applyFilter();
				},
				error: function(r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

		$scope.milestones = null;
		$scope.contentTypes = null;
		$scope.buyingStages = null;
		$scope.campaigns = null;
		$scope.users = null;
		$scope.content = null;
		$scope.filteredContent = null;
		$scope.pagedContent = null;

		$scope.pagination = {
			totalItems: 0,
			pageSize: 5,
			currentPage: 1,
			maxPage: 0,
			onPageChange: function(page) {
			},
			groupToPages: function() {
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
			getPageIndicator: function() {
				var start = ((($scope.pagination.currentPage - 1) * $scope.pagination.pageSize) + 1);
				var end = ($scope.pagination.currentPage * $scope.pagination.pageSize);

				if (end > $scope.pagination.totalItems) {
					end = $scope.pagination.totalItems;
				}

				return start + ' to ' + end + ' of ' + $scope.pagination.totalItems;
			}
		};

		$scope.search = {
			searchTerm: null,
			searchTermMinLength: 1,
			myTasks: false,
			contentTypes: null,
			milestones: null,
			buyingStages: null,
			campaigns: null,
			users: null,
			contentStage: 'content',
			applyFilter: function (reset) {
				$scope.filteredContent = $filter('filter')($scope.content, function (content) {
					if ($scope.search.contentStage === 'content' && (content.currentStep.name === 'concept' || content.currentStep.name === 'archive')) {
						return false;
					} else if ($scope.search.contentStage === 'concepts' && content.currentStep.name !== 'concept') {
						return false;
					} else if ($scope.search.contentStage === 'archived' && content.currentStep.name !== 'archive') {
						return false;
					}

					if ($scope.search.myTasks && content.author.id !== self.loggedInUser.id) {
						return false;
					}

					if ($.isArray($scope.search.contentTypes) && $scope.search.contentTypes.length > 0) {
						if ($.inArray(content.contentType, $scope.search.contentTypes) < 0) {
							return false;
						}
					}

					if ($.isArray($scope.search.milestones) && $scope.search.milestones.length > 0) {
						if ($.inArray(content.currentStep.name, $scope.search.milestones) < 0) {
							return false;
						}
					}

					if ($.isArray($scope.search.buyingStages) && $scope.search.buyingStages.length > 0) {
						if ($.inArray(content.buyingStage.toString(), $scope.search.buyingStages) < 0) {
							return false;
						}
					}

					if ($.isArray($scope.search.campaigns) && $scope.search.campaigns.length > 0) {
						if ($.inArray(content.campaign.id, $scope.search.campaigns) < 0) {
							return false;
						}
					}

					if ($.isArray($scope.search.users) && $scope.search.users.length > 0) {
						// TODO: FIX THIS!! IT WON'T WORK AS IS!!
						if ($.inArray(content.author.id, $scope.search.users) < 0) {
							return false;
						}
					}

					return true;
				});

				if (reset === true) {
					$scope.pagination.currentPage = 1;
				}

				$scope.pagination.totalItems = $scope.filteredContent.length;
				$scope.pagination.groupToPages();
			},
			clearFilter: function() {
				this.searchTerm = null;
				this.contentTypes = null;
				this.milestones = null;
				this.buyingStages = null;
				this.campaigns = null;
				this.users = null;

				this.applyFilter();
			},
			toggleContentStage: function(stage) {
				this.contentStage = stage;
				this.applyFilter();
			},
			toggleMyTasks: function() {
				$scope.search.myTasks = !$scope.search.myTasks;

				$scope.search.applyFilter();
			}
		};

		$scope.formatContentTypeItem = function (item, element, context) {
			return '<span class="' + launch.utils.getContentTypeIconClass(item.id) + '"></span> <span>' + item.text + '</span>';
		};

		$scope.formatMilestoneItem = function (item, element, context) {
			return '<span class="' + launch.utils.getWorkflowIconCssClass(item.id) + '"></span> <span>' + item.text + '</span>';
		};

		$scope.formatBuyingStageItem = function (item, element, context) {
			return '<span class="cl-icon cl-icon-personas-' + item.id + '"></span> <span>' + item.text + '</span>';
		};

		$scope.formatCampaignItem = function (item, element, context) {
			return '<span class="campaign-dot campaign-dot-' + item.id + '"></span> <span>' + item.text + '</span>';
		};

		$scope.formatUserItem = function (item, element, context) {
			var user = $.grep($scope.users, function(u, i) { return u.id === parseInt(item.id); });
			var style = (user.length === 1 && !launch.utils.isBlank(user[0].image)) ? ' style="background-image: ' + user[0].imageUrl() + '"' : '';

			return '<span class="user-image user-image-small"' + style + '></span> <span>' + item.text + '</span>';
		};

		$scope.formatWorkflowItem = function(item) {
			return launch.utils.getWorkflowIconCssClass(item.currentStep.name);
		};

		$scope.formatWorkflowTitle = function(item) {
			return launch.utils.titleCase(item.name);
		};

		$scope.formatContentTypeIcon = function (item) {
			return launch.utils.getContentTypeIconClass(item.contentType);
		};

		$scope.formatDate = function (date) {
			return launch.utils.formatDate(date);
		};

		$scope.highlightDate = function(date) {
			var dt = (new Date(date)).getTime();
			var today = (new Date(launch.utils.formatDate(new Date()))).getTime();

			if ((today - dt) < 172800000) {
				return true;
			}

			return false;
		};

		$scope.createConcept = function () {
			notificationService.info('WARNING!!', 'THIS IS NOT YET IMPLEMENTED!');
		};

		$scope.createContent = function () {
			notificationService.info('WARNING!!', 'THIS IS NOT YET IMPLEMENTED!');
		};

		$scope.saveFilter = function() {
			notificationService.info('WARNING!!', 'THIS IS NOT YET IMPLEMENTED!');
		};

		$scope.download = function() {
			notificationService.info('WARNING!!', 'THIS IS NOT YET IMPLEMENTED!');
		};

		self.init();
	}
]);