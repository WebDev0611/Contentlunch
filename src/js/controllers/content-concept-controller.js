﻿launch.module.controller('ContentConceptController', [
	'$scope', '$routeParams', '$filter', '$location', 'AuthService', 'UserService', 'ContentSettingsService', 'ConceptService', 'ContentService', 'NotificationService', function ($scope, $routeParams, $filter, $location, authService, userService, contentSettingsService, conceptService, contentService, notificationService) {
		var self = this;

		self.loggedInUser = null;

		self.init = function () {
			self.loggedInUser = authService.userInfo();
			self.refreshConcept();

			$scope.showCollaborate = (!$scope.isNewConcept && self.loggedInUser.hasModuleAccess('collaborate'));

			$scope.contentTypes = contentService.getContentTypes({
				success: function (r) {
				},
				error: function (r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});

			//TODO: POPULATE CAMPAIGNS FROM API!!
			$scope.campaigns = null;
			$scope.users = userService.getForAccount(self.loggedInUser.account.id, {
				success: function (r) {

				},
				error: function (r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		}

		self.refreshConcept = function() {
			var conceptId = parseInt($routeParams.conceptId);

			if (isNaN(conceptId)) {
				$scope.concept = conceptService.getNewContentConcept(self.loggedInUser);
				$scope.isNewConcept = true;
			} else {
				$scope.concept = conceptService.get(self.loggedInUser.account.id, conceptId, {
					success: function (r) {

					},
					error: function (r) {
						launch.utils.handleAjaxErrorResponse(r, notificationService);
					}
				});
				$scope.isNewConcept = false;
			}
		};

		$scope.hasError = launch.utils.isPropertyValid;
		$scope.errorMessage = launch.utils.getPropertyErrorMessage;
		$scope.forceDirty = false;
		$scope.isSaving = false;

		$scope.concept = null;
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
			if (!$scope.concept || $scope.concept.$resolved === false) {
				return;
			}

			$scope.forceDirty = true;

			var msg = launch.utils.validateAll($scope.concept);

			if (!launch.utils.isBlank(msg)) {
				notificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
				return;
			}

			var method = $scope.isNewConcept ? conceptService.add : conceptService.update;

			$scope.isSaving = true;

			method(self.loggedInUser.account.id, $scope.concept, {
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

		$scope.viewInCollaborate = function() {
			notificationService.info('WARNING!', 'THIS IS NOT YET IMPLEMENTED!!');
		};

		$scope.convertConcept = function() {
			notificationService.info('WARNING!', 'THIS IS NOT YET IMPLEMENTED!!');
		};

		self.init();
	}
]);