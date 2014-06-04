launch.module.controller('ContentConceptController', [
	'$scope', '$routeParams', '$filter', '$location', 'AuthService', 'UserService', 'ContentSettingsService', 'ContentService', 'CampaignService', 'NotificationService', function ($scope, $routeParams, $filter, $location, authService, userService, contentSettingsService, contentService, campaignService, notificationService) {
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
			self.refreshConcept();

			$scope.showCollaborate = (!$scope.isNewConcept && self.loggedInUser.hasModuleAccess('collaborate'));

			$scope.contentTypes = contentService.getContentTypes(self.ajaxHandler);
			$scope.campaigns = campaignService.query(self.loggedInUser.account.id, self.ajaxHandler);
			$scope.users = userService.getForAccount(self.loggedInUser.account.id, self.ajaxHandler);
		}

		self.refreshConcept = function() {
			var contentId = parseInt($routeParams.contentId);

			if (isNaN(contentId)) {
				$scope.content = contentService.getNewContentConcept(self.loggedInUser);
				$scope.isNewConcept = true;
			} else {
				$scope.content = contentService.get(self.loggedInUser.account.id, contentId, self.ajaxHandler);
				$scope.isNewConcept = false;
			}
		};

		$scope.hasError = launch.utils.isPropertyValid;
		$scope.errorMessage = launch.utils.getPropertyErrorMessage;
		$scope.forceDirty = false;
		$scope.isSaving = false;

		$scope.content = null;
		$scope.contentTypes = null;
		$scope.campaigns = null;
		$scope.users = null;
		$scope.isNewConcept = true;
		$scope.isContentConcept = true;
		$scope.showCollaborate = false;

		$scope.formatContentTypeItem = launch.utils.formatContentTypeItem;
		$scope.formatCampaignItem = launch.utils.formatCampaignItem;

		$scope.formatUserItem = function (item, element, context) {
			var user = $.grep($scope.users, function (u, i) { return u.id === parseInt(item.id); });
			var style = (user.length === 1 && !launch.utils.isBlank(user[0].image)) ? ' style="background-image: ' + user[0].imageUrl() + '"' : '';

			return '<span class="user-image user-image-small"' + style + '></span> <span>' + item.text + '</span>';
		};

		$scope.saveConcept = function() {
			if (!$scope.content || $scope.content.$resolved === false) {
				return;
			}

			$scope.forceDirty = true;

			var msg = launch.utils.validateAll($scope.content);

			if (!launch.utils.isBlank(msg)) {
				notificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
				return;
			}

			var method = $scope.isNewConcept ? contentService.add : contentService.update;

			$scope.isSaving = true;

			method(self.loggedInUser.account.id, $scope.content, {
				success: function(r) {
					$scope.isSaving = false;

					var successMsg = $scope.isNewConcept ? 'Successfully created new concept!' : 'Successfully updated concept!';

					notificationService.success('Success!', successMsg);

					if ($scope.isNewConcept) {
						$location.path('/create/concept/edit/content/' + r.id);
					} else {
						self.refreshConcept();
					}
				},
				error: function (r) {
					$scope.isSaving = false;
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

		$scope.viewInCollaborate = function () {
			$location.path('/collaborate/content/' + $scope.content.id);
		};

		$scope.convertConcept = function() {
			$scope.content.status = 2;
			$scope.content.concept = $scope.content.body;

			contentService.update(self.loggedInUser.account.id, $scope.content, {
				success: function(r) {
					$location.path('/create/content/edit/' + $scope.content.id);
				},
				error: function(r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

		$scope.updateContentType = function() {
			var contentTypeName = $scope.content.contentType.name;
			var contentType = $.grep($scope.contentTypes, function(ct) { return ct.name === contentTypeName; });

			$scope.content.contentType = contentType[0];
		};

		$scope.updateAuthor = function () {
			var userId = parseInt($scope.content.author.id);
			var user = $.grep($scope.users, function (u) { return u.id === userId; });

			$scope.content.author = user[0];
		};

		$scope.updateCampaign = function () {
			var campaignId = parseInt($scope.content.campaign.id);
			var campaign = $.grep($scope.campaigns, function (u) { return u.id === campaignId; });

			$scope.content.campaign = campaign[0];
		};

		self.init();
	}
]);