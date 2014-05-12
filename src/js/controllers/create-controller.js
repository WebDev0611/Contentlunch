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
						return { name: bs, id: i };
					});
				},
				error: function(r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});

			//TODO: POPULATE CAMPAIGNS FROM API!!
			$scope.campaigns = null;
			$scope.users = userService.getForAccount(self.loggedInUser.account.id);

			self.loadContent();
		};

		self.loadContent = function() {
			if ($scope.search.contentStage === 'content') {
				$scope.content = contentService.query(self.loggedInUser.account.id, null, {
					success: function (r) {
						$scope.search.applyFilter();
					},
					error: function (r) {
						launch.utils.handleAjaxErrorResponse(r, notificationService);
					}
				});
			} else if ($scope.search.contentStage === 'concept') {
				$scope.content = contentService.query(self.loggedInUser.account.id, null, {
					success: function (r) {
						$scope.search.applyFilter();
					},
					error: function (r) {
						launch.utils.handleAjaxErrorResponse(r, notificationService);
					}
				});
			}
		};

		$scope.milestones = null;
		$scope.contentTypes = null;
		$scope.buyingStages = null;
		$scope.campaigns = null;
		$scope.users = null;
		$scope.content = null;
		$scope.filteredContent = null;
		$scope.pagedContent = null;

		$scope.formatContentTypeItem = launch.utils.formatContentTypeItem;
		$scope.formatCampaignItem = launch.utils.formatCampaignItem;
		$scope.formatBuyingStageItem = launch.utils.formatBuyingStageItem;
		$scope.formatMilestoneItem = launch.utils.formatMilestoneItem;
		$scope.formatWorkflowItem = launch.utils.getWorkflowIconCssClass;
		$scope.formatContentTypeIcon = launch.utils.getContentTypeIconClass;
		
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
						return ($.grep($scope.search.users, function(uid) { return parseInt(uid) === content.author.id; }).length > 0);
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

		$scope.formatUserItem = function (item, element, context) {
			var user = $.grep($scope.users, function(u, i) { return u.id === parseInt(item.id); });
			var style = (user.length === 1 && !launch.utils.isBlank(user[0].image)) ? ' style="background-image: ' + user[0].imageUrl() + '"' : '';

			return '<span class="user-image user-image-small"' + style + '></span> <span>' + item.text + '</span>';
		};

		$scope.formatWorkflowTitle = function(item) {
			return launch.utils.titleCase(item.name);
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

		$scope.createNew = function (createType) {
			if (launch.utils.isBlank(createType)) {
				return;
			}

			switch (createType.toLowerCase()) {
				case 'content-concept':
					$location.path('/create/concept/create/content');
					return;
				case 'campaign-concept':
					$location.path('/create/concept/create/campaign');
					return;
				case 'content':
					$location.path('/create/content/create');
					return;
				default:
			}
		};

		$scope.saveFilter = function() {
			notificationService.info('WARNING!!', 'THIS IS NOT YET IMPLEMENTED!');
		};

		$scope.download = function() {
			notificationService.info('WARNING!!', 'THIS IS NOT YET IMPLEMENTED!');
		};

		$scope.handleNextStep = function (content) {
			if (!content || !content.nextStep || launch.utils.isBlank(content.nextStep.name)) {
				notificationService.error('INVALID NEXT STEP', 'WHAT DO WE DO HERE?');
				return;
			}

			switch (content.nextStep.name.toLowerCase()) {
				case 'create':
					$location.path('create/concept/edit/content/' + content.id);
					break;
				case 'edit':
					$location.path('create/content/edit/' + content.id);
					break;
				case 'approve':
					// TODO: IMPLEMENT APPROVE STEP!!
					notificationService.info('NOT IMPLEMENTED!', 'THIS FEATURE IS NOT YET IMPLEMENTED!');
					break;
				case 'launch':
					$location.path('create/content/launch/' + content.id);
					break;
				case 'promote':
					$location.path('create/content/promote/' + content.id);
					break;
				case 'archive':
					// TODO: IMPLEMENT ARCHIVE STEP!!
					notificationService.info('NOT IMPLEMENTED!', 'THIS FEATURE IS NOT YET IMPLEMENTED!');
					break;
				case 'restore':
					// TODO: IMPLEMENT RESTORE STEP!!
					notificationService.info('NOT IMPLEMENTED!', 'THIS FEATURE IS NOT YET IMPLEMENTED!');
					break;
				default:
					notificationService.info('Unknown Workflow Step', 'The workflow step "' + content.nextStep.name + '" is not valid.');
					break;
			}
		};

		self.init();
	}
]);