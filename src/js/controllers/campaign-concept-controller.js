launch.module.controller('CampaignConceptController', [
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

			$scope.users = userService.getForAccount(self.loggedInUser.account.id, null, self.ajaxHandler);
		}

		self.refreshConcept = function () {
			var campaignId = parseInt($routeParams.campaignId);

			if (isNaN(campaignId)) {
				$scope.campaign = campaignService.getNewCampaignConcept(self.loggedInUser);
				$scope.isNewConcept = true;
			} else {
				$scope.campaign = campaignService.get(self.loggedInUser.account.id, campaignId, {
					success: function (r) {
						if ($scope.campaign.status > 0) {
							$location.path('/calendar/campaigns/' + $scope.campaign.id);
							return;
						}

						$scope.isCollaborator = (self.loggedInUser.id === $scope.campaign.user.id ||
							$.grep($scope.campaign.collaborators, function (c) { return c.id === self.loggedInUser.id; }).length > 0);

						$scope.isCollaborator = ($scope.isCollaborator || self.loggedInUser.hasPrivilege('create_edit_ideas_other'));

						if (!$scope.isCollaborator) {
							return;
						}

						if (self.loggedInUser.id === $scope.campaign.user.id) {
							$scope.canConvertConept = self.loggedInUser.hasPrivilege('create_execute_convert_concept_own');
							$scope.canEditCampaign = true;
						} else {
							$scope.canConvertConept = self.loggedInUser.hasPrivilege('create_execute_convert_concept_other');
							$scope.canEditCampaign = self.loggedInUser.hasPrivilege('create_edit_ideas_other');
						}

						self.refreshComments();

						$scope.guestCollaborators = campaignService.queryGuestCollaborators(self.loggedInUser.account.id, campaignId, null, self.ajaxHandler);
					},
					error: self.ajaxHandler.error
				});
				$scope.isNewConcept = false;
			}
		};

		self.refreshComments = function () {
			$scope.comments = campaignService.queryComments(self.loggedInUser.account.id, $scope.campaign.id, null, self.ajaxHandler);
		};

		$scope.hasError = launch.utils.isPropertyValid;
		$scope.errorMessage = launch.utils.getPropertyErrorMessage;
		$scope.forceDirty = false;
		$scope.isSaving = false;

		$scope.campaign = null;
		$scope.comments = null;
		$scope.users = null;
		$scope.collaborators = null;
		$scope.guestCollaborators = null;
		$scope.isCollaborator = true;
		$scope.isNewConcept = true;

		$scope.canEditCampaign = true;
		$scope.showCollaborate = false;
		$scope.canConvertConept = false;

		$scope.formatUserItem = function (item, element, context) {
			var user = $.grep($scope.users, function (u, i) { return u.id === parseInt(item.id); });
			var style = (user.length === 1 && !launch.utils.isBlank(user[0].image)) ? ' style="background-image: ' + user[0].imageUrl() + '"' : '';

			return '<span class="user-image user-image-small"' + style + '></span> <span>' + item.text + '</span>';
		};

		$scope.saveConcept = function () {
			if (!$scope.campaign || $scope.campaign.$resolved === false) {
				return;
			}

			$scope.forceDirty = true;

			var msg = launch.utils.validateAll($scope.campaign);

			if (!launch.utils.isBlank(msg)) {
				notificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
				return;
			}

			var method = $scope.isNewConcept ? campaignService.add : campaignService.update;

			$scope.isSaving = true;

			method(self.loggedInUser.account.id, $scope.campaign, {
				success: function (r) {
					$scope.isSaving = false;

					var successMsg = $scope.isNewConcept ? 'Successfully created new concept!' : 'Successfully updated concept!';

					notificationService.success('Success!', successMsg);

					if ($scope.isNewConcept) {
						$location.path('/create/concept/edit/campaign/' + r.id);
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

		$scope.convertConcept = function() {
			$scope.campaign.status = 1;
			$scope.campaign.concept = $scope.campaign.description;

			campaignService.update(self.loggedInUser.account.id, $scope.campaign, {
				success: function (r) {
					$location.path('/calendar/campaigns/' + $scope.campaign.id);
				},
				error: function (r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

		$scope.addComment = function (message) {
			launch.utils.insertComment(message, $scope.campaign.id, self.loggedInUser, campaignService, notificationService, {
				success: function (r) {
					self.refreshComments();
				},
				error: self.ajaxHandler.error
			});
		};

		self.init();
	}
]);