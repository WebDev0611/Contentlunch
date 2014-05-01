launch.module.controller('CreateController', [
	'$scope', '$location', 'AuthService', 'UserService', 'ContentSettingsService', 'ContentService', 'NotificationService', function ($scope, $location, authService, userService, contentSettingsService, contentService, notificationService) {
		var self = this;

		self.loggedInUser = null;

		self.init = function () {
			self.loggedInUser = authService.userInfo();

			$scope.contentTypes = launch.config.CONTENT_TYPES;

			var contentSettings = contentSettingsService.get(self.loggedInUser.account.id, {
				success: function(r) {
					$scope.buyingStages = contentSettings.personaProperties;
				},
				error: function(r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});

			//TODO: POPULATE CAMPAIGNS FROM API!!
			$scope.campaigns = null;
			$scope.users = userService.query();

			$scope.content = contentService.query(null, {
				success: function(r) {
					
				},
				error: function(r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

		$scope.contentTypes = null;
		$scope.buyingStages = null;
		$scope.campaigns = null;
		$scope.users = null;

		$scope.pagination = {
			totalItems: 0,
			pageSize: 10,
			currentPage: 1,
			onPageChange: function(page) {
				
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
			applyFilter: function () {
				// TODO: APPLY FILTER!!
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
			}
		};

		$scope.formatContentTypeItem = function (item, element, context) {
			return launch.utils.getContentTypeIconClass(item.contentType);
		};

		$scope.formatWorkflowItem = function(item) {
			return launch.utils.getWorkflowIconCssClass(item.currentStep.name);
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

		self.init();
	}
]);