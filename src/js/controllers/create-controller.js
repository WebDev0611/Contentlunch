launch.module.controller('CreateController', [
	'$scope', '$filter', '$location', '$modal', 'AuthService', 'UserService', 'ContentSettingsService', 'ContentService', 'CampaignService', 'NotificationService', function($scope, $filter, $location, $modal, authService, userService, contentSettingsService, contentService, campaignService, notificationService) {
		var self = this;

		self.loggedInUser = null;

		self.ajaxHandler = {
			success: function(r) {

			},
			error: function(r) {
				launch.utils.handleAjaxErrorResponse(r, notificationService);
			}
		};

		self.init = function() {
			self.loggedInUser = authService.userInfo();

			$scope.canViewConcepts = self.loggedInUser.hasPrivilege(['create_view_ideas_other', 'create_execute_ideas_own']);
			$scope.canViewContent = self.loggedInUser.hasPrivilege(['create_execute_content_own', 'create_view_content_other_unapproved', 'create_view_content_other']);
			$scope.canCreateContentConcept = self.loggedInUser.hasPrivilege('create_execute_ideas_own');
			$scope.canCreateCampaignConcept = self.loggedInUser.hasPrivilege('calendar_execute_campaigns_own');
			$scope.canCreateContent = self.loggedInUser.hasPrivilege('create_execute_content_own');
			$scope.editConceptSelf = self.loggedInUser.hasPrivilege('create_execute_ideas_own');
			$scope.editConceptOthers = self.loggedInUser.hasPrivilege('create_edit_ideas_other');
			$scope.editContentSelf = self.loggedInUser.hasPrivilege('create_execute_content_own');
			$scope.editContentOthers = self.loggedInUser.hasPrivilege(['create_edit_content_other', 'create_edit_content_other_unapproved']);

			// TODO: WE NEED A PRIVILEGE THAT ALLOWS A USER TO DELETE CONTENT!!
			$scope.canDelete = true; //self.loggedInUser.hasPrivilege('');

			if (!$scope.canViewConcepts && !$scope.canViewContent) {
				$location.path('/');
			}

			if (!$scope.canViewContent) {
				$scope.search.contentStage = 'concepts';
			}

			$scope.steps = [
				{ name: 'create', title: 'Created' },
				{ name: 'approve', title: 'Approved' },
				{ name: 'launch', title: 'Launched' }
			];

			var contentSettings = contentSettingsService.get(self.loggedInUser.account.id, {
				success: function(r) {
					if ($.isArray(contentSettings.personaProperties)) {
						$scope.buyingStages = $.map(contentSettings.personaProperties, function(bs, i) {
							return { name: bs, id: i };
						});
					}
				},
				error: function(r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});

			$scope.contentTypes = contentService.getContentTypes(self.ajaxHandler);
			$scope.campaigns = campaignService.query(self.loggedInUser.account.id, self.ajaxHandler);
			$scope.users = userService.getForAccount(self.loggedInUser.account.id, null, self.ajaxHandler);

			self.loadContent(true);
		};

		self.loadContent = function (resetFilters) {
			// NEED TO PAUSE HERE DUE TO A RACE SITUATION BETWEEN TRYING TO FETCH CAMPAIGNS
			// AND THE CONTENT ITEM. THE SELECT2 DROP-DOWN NEEDS TO GET ITS OPTIONS IN PLACE BEFORE WE
			// SET THE MODEL FOR THE CONTROL. COMPLETE HACK DUE TO LIMITATIONS OF THE CONTROL.
			if (!$scope.campaigns || !$scope.campaigns.$resolved) {
				window.setTimeout(function() { self.loadContent(resetFilters); }, 200);
				$scope.content = null;
				return;
			}

			var params = null;
			// $scope.campaign may be inherited from parent controller
			if ($scope.campaign) {
				params = {
					campaign_id: $scope.campaign.id
				};
			}

			if (resetFilters) {
				if (!!self.loggedInUser.preferences && !!self.loggedInUser.preferences.create) {
					$scope.search.searchTerm = (launch.utils.isBlank(self.loggedInUser.preferences.create.searchTerm)) ? null : self.loggedInUser.preferences.create.searchTerm;
					$scope.search.myTasks = (!!self.loggedInUser.preferences.create.myTasks) ? true : false;
					$scope.search.contentTypes = $.isArray(self.loggedInUser.preferences.create.contentTypes) ? self.loggedInUser.preferences.create.contentTypes : [];
					$scope.search.steps = $.isArray(self.loggedInUser.preferences.create.steps) ? self.loggedInUser.preferences.create.steps : [];
					$scope.search.buyingStages = $.isArray(self.loggedInUser.preferences.create.buyingStages) ? self.loggedInUser.preferences.create.buyingStages : [];
					$scope.search.campaigns = $.isArray(self.loggedInUser.preferences.create.campaigns) ? self.loggedInUser.preferences.create.campaigns : [];
					$scope.search.users = $.isArray(self.loggedInUser.preferences.create.users) ? self.loggedInUser.preferences.create.users : [];
					$scope.search.contentStage = (launch.utils.isBlank(self.loggedInUser.preferences.create.contentStage)) ? 'content' : self.loggedInUser.preferences.create.contentStage;
				} else {
					$scope.search.clearFilter();
				}

				$scope.contentTypeOpen = $scope.search.contentTypes.length > 0;
				$scope.stepOpen = $scope.search.steps.length > 0;
				$scope.buyingStageOpen = $scope.search.buyingStages.length > 0;
				$scope.campaignOpen = $scope.search.campaigns.length > 0;
				$scope.contentCreatorOpen = $scope.search.users.length > 0;
			}

			$scope.content = contentService.query(self.loggedInUser.account.id, params, {
				success: function (r) {
					$scope.applySort();
					$scope.search.applyFilter(true);
				},
				error: function(r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

		self.verifyArchive = function(content, isArchived) {
			var verb = isArchived ? 'restore' : 'archive';

			if (!$scope.canEditContent(content)) {
				notificationService.error('Error!', 'You do not have sufficient privileges to ' + verb + ' content. Please contact your administrator for more information.');
				return;
			}

			$modal.open({
				templateUrl: 'confirm.html',
				controller: [
					'$scope', '$modalInstance', function(scope, instance) {
						scope.message = 'Are you sure you want to ' + verb + ' this content?';
						scope.okButtonText = launch.utils.titleCase(verb);
						scope.cancelButtonText = 'Cancel';
						scope.onOk = function() {
							content.archived = !isArchived;

							contentService.update(self.loggedInUser.account.id, content, {
								success: function(r) {
									self.loadContent();
								},
								error: self.ajaxHandler.error
							});

							instance.close();
						};
						scope.onCancel = function() {
							instance.dismiss('cancel');
						};
					}
				]
			});
		};

		$scope.steps = null;
		$scope.contentTypes = null;
		$scope.buyingStages = null;
		$scope.campaigns = null;
		$scope.users = null;
		$scope.content = null;
		$scope.filteredContent = null;
		$scope.pagedContent = null;

		$scope.canViewContent = false;
		$scope.canViewConcepts = false;
		$scope.canCreateContentConcept = false;
		$scope.canCreateCampaignConcept = false;
		$scope.canCreateContent = false;
		$scope.editContentSelf = false;
		$scope.editContentOthers = false;
		$scope.editConceptSelf = false;
		$scope.editConceptOthers = false;
		$scope.canDelete = false;

		$scope.formatContentTypeItem = launch.utils.formatContentTypeItem;
		$scope.formatCampaignItem = launch.utils.formatCampaignItem;
		$scope.formatBuyingStageItem = launch.utils.formatBuyingStageItem;
		$scope.formatStepItem = launch.utils.formatStepItem;
		$scope.formatWorkflowItem = launch.utils.getWorkflowIconCssClass;
		$scope.formatContentTypeIcon = launch.utils.getContentTypeIconClass;
		$scope.formatDate = launch.utils.formatDate;

		$scope.contentTypeOpen = false;
		$scope.stepOpen = false;
		$scope.buyingStageOpen = false;
		$scope.campaignOpen = false;
		$scope.contentCreatorOpen = false;

		$scope.pagination = {
			totalItems: 0,
			currentSort: 'title',
			currentSortDirection: null,
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
			},
			reset: function (sort) {
				if (!launch.utils.isBlank(sort)) {
					self.currentSort = sort;
				}

				$scope.pagination.currentPage = 1;
			}
		};

		$scope.search = {
			searchTerm: null,
			searchTermMinLength: 1,
			myTasks: false,
			contentTypes: [],
			steps: [],
			buyingStages: [],
			campaigns: [],
			users: [],
			contentStage: 'content',
			changeSearchTerm: function() {
				if (launch.utils.isBlank($scope.search.searchTerm) || $scope.search.searchTerm.length >= $scope.search.searchTermMinLength) {
					$scope.search.applyFilter(true);
				}
			},
			applyFilter: function (reset) {
				if (!$scope.content || !$scope.content.$resolved) {
					return;
				}

				$scope.filteredContent = $filter('filter')($scope.content, function(content) {
					if ($scope.search.contentStage === 'content' && (content.currentStep() === 'concept' || content.currentStep() === 'archive')) {
						return false;
					} else if ($scope.search.contentStage === 'concepts' && content.currentStep() !== 'concept') {
						return false;
					} else if ($scope.search.contentStage === 'archived' && content.currentStep() !== 'archive') {
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

					if ($.isArray($scope.search.steps) && $scope.search.steps.length > 0) {
						if ($.inArray(content.currentStep(), $scope.search.steps) < 0) {
							return false;
						}
					}

					if ($.isArray($scope.search.buyingStages) && $scope.search.buyingStages.length > 0) {
						var buyingStage = launch.utils.isBlank(content.buyingStage) ? '' : content.buyingStage.toString();

						if ($.inArray(buyingStage, $scope.search.buyingStages) < 0) {
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
						return ($.grep($scope.search.users, function(uid) { return parseInt(uid) === content.author.id; }).length > 0);
					}

					return (launch.utils.isBlank($scope.search.searchTerm) ? true : content.matchSearchTerm($scope.search.searchTerm));
				});

				if (reset === true) {
					$scope.pagination.reset();
				}

				$scope.pagination.totalItems = $scope.filteredContent.length;
				$scope.pagination.groupToPages();
			},
			clearFilter: function() {
				$scope.search.searchTerm = null;
				$scope.search.contentTypes = [];
				$scope.search.steps = [];
				$scope.search.buyingStages = [];
				$scope.search.campaigns = [];
				$scope.search.users = [];

				$scope.search.applyFilter(true);
			},
			toggleContentStage: function(stage) {
				$scope.search.searchTerm = null;
				$scope.search.myTasks = false;
				$scope.search.contentTypes = null;
				$scope.search.steps = null;
				$scope.search.buyingStages = null;
				$scope.search.campaigns = null;
				$scope.search.users = null;

				$scope.search.contentStage = stage;

				$.each($scope.content, function (i, c) { c.isSelected = false; });

				$scope.pagination.reset('title');

				this.applyFilter();
			},
			toggleMyTasks: function(mine) {
				$scope.search.myTasks = !!mine;

				$scope.search.applyFilter(true);
			}
		};

		$scope.formatUserItem = function(item, element, context) {
			var user = $.grep($scope.users, function(u, i) { return u.id === parseInt(item.id); });
			var style = (user.length === 1 && !launch.utils.isBlank(user[0].image)) ? ' style="background-image: ' + user[0].imageUrl() + '"' : '';

			return '<span class="user-image user-image-small"' + style + '></span> <span>' + item.text + '</span>';
		};

		$scope.formatWorkflowTitle = function(item) {
			return launch.utils.titleCase(item);
		};

		$scope.highlightDate = function(date) {
			var dt = (new Date(date)).getTime();
			var today = (new Date(launch.utils.formatDate(new Date()))).getTime();

			if ((today - dt) < 172800000) {
				return true;
			}

			return false;
		};

		$scope.createNew = function(createType) {
			if (launch.utils.isBlank(createType)) {
				return;
			}

			switch (createType.toLowerCase()) {
				case 'content-concept':
					$location.path('/create/concept/new/content');
					return;
				case 'campaign-concept':
					$location.path('/create/concept/new/campaign');
					return;
				case 'content':
					$location.path('/create/content/new');
					return;
				default:
			}
		};

		$scope.saveFilter = function () {
			userService.savePreferences(self.loggedInUser.id, 'create', $scope.search, {
				success: function(r) {
					notificationService.success('Success!', 'Create default filters saved!');
					self.loggedInUser = authService.fetchCurrentUser({
						success: function(r) {
							
						},
						error: self.ajaxHandler.error
					});
				},
				error: self.ajaxHandler.error
			});
		};

		$scope.deleteSelected = function() {
			if ($scope.search.contentStage === 'content') {
				return;
			}

			var itemsToDelete = $.grep($scope.content, function(c) { return c.isSelected; });

			$.each(itemsToDelete, function(i, c) {
				contentService.delete(self.loggedInUser.account.id, c, {
					success: function(r) {
						$scope.content = $.grep($scope.content, function(ct) { return ct.id !== c.id; });
						$scope.search.applyFilter(false);
					},
					error: self.ajaxHandler.error
				});
			});
		};

		$scope.handleCurrentStep = function(content) {
			if (!content || launch.utils.isBlank(content.nextStep())) {
				notificationService.info('Unknown Workflow Step', 'The workflow step "' + content.currentStep() + '" is not valid.');
				return;
			}

			if (!$scope.canEditContent(content)) {
				notificationService.error('Error!', 'Your are unauthorized to view this item.');
				return;
			}

			switch (content.currentStep().toLowerCase()) {
				case 'create':
					$location.path('create/concept/edit/content/' + content.id);
					break;
					//case 'review':
					//case 'approve':
					//	$location.path('create/content/edit/' + content.id);
					//	break;
					//case 'launch':
					//	$location.path('create/content/launch/' + content.id);
					//	break;
					//case 'promote':
					//	$location.path('create/content/promote/' + content.id);
					//	break;
				//case 'archive':
				case 'restore':
					self.verifyArchive(content, content.archived);
					break;
				default:
					$location.path('create/content/edit/' + content.id);
					//notificationService.info('Unknown Workflow Step', 'The workflow step "' + content.currentStep() + '" is not valid.');
					break;
			}
		};

		$scope.handleNextStep = function(content) {
			if (!content || launch.utils.isBlank(content.nextStep())) {
				notificationService.info('Unknown Workflow Step', 'The workflow step "' + content.nextStep() + '" is not valid.');
				return;
			}

			if (!$scope.canEditContent(content)) {
				notificationService.error('Error!', 'Your are unauthorized to view this item.');
				return;
			}

			switch (content.nextStep().toLowerCase()) {
				case 'create':
					$location.path('create/concept/edit/content/' + content.id);
					break;
				//case 'review':
				//case 'approve':
				//	$location.path('create/content/edit/' + content.id);
				//	break;
				//case 'launch':
				//	$location.path('create/content/launch/' + content.id);
				//	break;
				//case 'promote':
				//	$location.path('create/content/promote/' + content.id);
				//	break;
				case 'archive':
				case 'restore':
					self.verifyArchive(content, content.archived);
					break;
				default:
					$location.path('create/content/edit/' + content.id);
					//notificationService.info('Unknown Workflow Step', 'The workflow step "' + content.nextStep() + '" is not valid.');
					break;
			}
		};

		$scope.canEditContent = function(content) {
			if (!content || !content.author) {
				return false;
			}

			if (content.author.id === self.loggedInUser.id) {
				return content.status === 0 ? $scope.editConceptSelf : $scope.editContentSelf;
			} else if ($.grep(content.collaborators, function(c) { return c.id === self.loggedInUser.id; }).length > 0) {
				return true;
			} else {
				return content.status === 0 ? $scope.editConceptOthers : $scope.editContentOthers;
			}
		};

		$scope.applySort = function (sort) {
			console.log('ENTERING APPLY SORT!');
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
				$scope.pagination.currentSortDirection = 'asc';
			}

			if (!$.isArray($scope.content) || $scope.content.length === 0 || !$scope.content.$resolved) {
				return;
			}

			$scope.content.sort(function(a, b) {
				if (!a && !b) { return 0; }
				if (!!a && !b) { return -1; }
				if (!a && !!b) { return 1; }

				if (a.id === b.id) {
					return 0;
				}

				if ($scope.pagination.currentSort === 'title') {
					if (launch.utils.isBlank(a.title) && launch.utils.isBlank(b.title)) { return 0; }
					if (!launch.utils.isBlank(a.title) && launch.utils.isBlank(b.title)) { return -1; }
					if (launch.utils.isBlank(a.title) && !launch.utils.isBlank(b.title)) { return 1; }

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
					if (!!a.author && !b.author) { return -1; }
					if (!a.author && !!b.author) { return 1; }

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

				if ($scope.pagination.currentSort === 'persona') {
					if (launch.utils.isBlank(a.persona) && launch.utils.isBlank(b.persona)) { return 0; }
					if (!launch.utils.isBlank(a.persona) && launch.utils.isBlank(b.persona)) { return -1; }
					if (launch.utils.isBlank(a.persona) && !launch.utils.isBlank(b.persona)) { return 1; }

					if (a.persona.toLowerCase() === b.persona.toLowerCase()) {
						return 0;
					}

					if ($scope.pagination.currentSortDirection === 'asc') {
						return (a.persona.toLowerCase() < b.persona.toLowerCase()) ? -1 : 1;
					} else {
						return (a.persona.toLowerCase() > b.persona.toLowerCase()) ? -1 : 1;
					}
				}

				if ($scope.pagination.currentSort === 'buyingstage') {
					if (launch.utils.isBlank(a.buyingStage) && launch.utils.isBlank(b.buyingStage)) { return 0; }
					if (!launch.utils.isBlank(a.buyingStage) && launch.utils.isBlank(b.buyingStage)) { return -1; }
					if (launch.utils.isBlank(a.buyingStage) && !launch.utils.isBlank(b.buyingStage)) { return 1; }

					if (a.buyingStage.toLowerCase() === b.buyingStage.toLowerCase()) {
						return 0;
					}

					if ($scope.pagination.currentSortDirection === 'asc') {
						return (a.buyingStage.toLowerCase() < b.buyingStage.toLowerCase()) ? -1 : 1;
					} else {
						return (a.buyingStage.toLowerCase() > b.buyingStage.toLowerCase()) ? -1 : 1;
					}
				}

				if ($scope.pagination.currentSort === 'currentstep' || $scope.pagination.currentSort === 'nextstep') {
					if (a.status === b.status) {
						return 0;
					}

					if ($scope.pagination.currentSortDirection === 'asc') {
						return (a.status < b.status) ? -1 : 1;
					} else {
						return (a.status > b.status) ? -1 : 1;
					}
				}

				return 0;
			});

			$scope.search.applyFilter(false);
		};

		self.init();
	}
]);