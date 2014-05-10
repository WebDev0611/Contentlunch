launch.module.controller('ContentConceptController', [
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
		$scope.concept = null;
		$scope.contentTypes = null;
		$scope.campaigns = null;
		$scope.users = null;
		$scope.isNewConcept = true;
		$scope.showCollaborate = false;

		$scope.initSelection = function(element, callback) {
			callback($(element).data('$ngModelController').$modelValue);
		};

		$scope.formatContentTypeItem = function(item, element, context) {
			return '<span class="' + launch.utils.getContentTypeIconClass(item.id) + '"></span> <span>' + item.text + '</span>';
		};

		$scope.formatCampaignItem = function (item, element, context) {
			return '<span class="campaign-dot campaign-dot-' + item.id + '"></span> <span>' + item.text + '</span>';
		};

		$scope.formatUserItem = function (item, element, context) {
			var user = $.grep($scope.users, function (u, i) { return u.id === parseInt(item.id); });
			var style = (user.length === 1 && !launch.utils.isBlank(user[0].image)) ? ' style="background-image: ' + user[0].imageUrl() + '"' : '';

			return '<span class="user-image user-image-small"' + style + '></span> <span>' + item.text + '</span>';
		};

		self.init();
	}
]);