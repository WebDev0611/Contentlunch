launch.module.controller('CreateController', [
	'$scope', '$location', 'AuthService', 'UserService', 'ContentSettingsService', 'NotificationService', function ($scope, $location, authService, userService, contentSettingsService, notificationService) {
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

			//TODO: POPULATE CONTENT FROM API!!
			$scope.content = [
				{ title: 'Sample Audio', contentType: 'audio', author: { id: 2, name: 'Test	site_admin' }, persona: 'CMO', buyingStage: 1, currentStep: '', nextStep: '' },
				{ title: 'Sample Blog Post', contentType: 'blog_post', author: { id: 3, name: 'Test	creator' }, persona: 'VP Sales', buyingStage: 2, currentStep: '', nextStep: '' },
				{ title: 'Sample Case Study', contentType: 'case_study', author: { id: 4, name: 'Test	manager' }, persona: 'Sales Rep', buyingStage: 3, currentStep: '', nextStep: '' },
				{ title: 'Sample eBook', contentType: 'ebook', author: { id: 5, name: 'Test	editor' }, persona: 'Product Manager', buyingStage: 4, currentStep: '', nextStep: '' },
				{ title: 'Sample eMail', contentType: 'email', author: { id: 6, name: 'Test	client' }, persona: 'CMO', buyingStage: 5, currentStep: '', nextStep: '' },
				{ title: 'Sample Facebook Post', contentType: 'facebook_post', author: { id: 2, name: 'Test	site_admin' }, persona: 'VP Sales', buyingStage: 1, currentStep: '', nextStep: '' },
				{ title: 'Sample Google Drive', contentType: 'google_drive', author: { id: 3, name: 'Test	creator' }, persona: 'Sales Rep', buyingStage: 2, currentStep: '', nextStep: '' },
				{ title: 'Sample Landing Page', contentType: 'landing_page', author: { id: 4, name: 'Test	manager' }, persona: 'Product Manager', buyingStage: 3, currentStep: '', nextStep: '' },
				{ title: 'Sample LinkedIn', contentType: 'linkedin', author: { id: 5, name: 'Test	editor' }, persona: 'CMO', buyingStage: 4, currentStep: '', nextStep: '' },
				{ title: 'Sample Photo', contentType: 'photo', author: { id: 6, name: 'Test	client' }, persona: 'VP Sales', buyingStage: 5, currentStep: '', nextStep: '' },
				{ title: 'Sample Salesforce Asset', contentType: 'salesforce_asset', author: { id: 2, name: 'Test	site_admin' }, persona: 'Sales Rep', buyingStage: 1, currentStep: '', nextStep: '' },
				{ title: 'Sample Twitter', contentType: 'twitter', author: { id: 3, name: 'Test	creator' }, persona: 'Product Manager', buyingStage: 2, currentStep: '', nextStep: '' },
				{ title: 'Sample Video', contentType: 'video', author: { id: 4, name: 'Test	manager' }, persona: 'CMO', buyingStage: 3, currentStep: '', nextStep: '' },
				{ title: 'Sample Whitepaper', contentType: 'whitepaper', author: { id: 5, name: 'Test	editor' }, persona: 'VP Sales', buyingStage: 4, currentStep: '', nextStep: '' }
			];
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
			return '<span class="' + launch.utils.getContentTypeIconClass(item.id) + '"></span> <span>' + item.text + '</span>';
		};

		self.init();
	}
]);