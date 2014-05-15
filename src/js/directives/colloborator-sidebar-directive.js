launch.module.directive('collaboratorSidebar', function($modal, $window, $location, AuthService, NotificationService, ContentService, CampaignService) {
	return {
		templateUrl: '/assets/views/colloborator-sidebar.html',
		scope: {
			users: '=users',
			itemId: '=itemId',
			itemType: '=itemType',
			addCollaboratorCallback: '=addCollaboratorCallback',
			removeCollaboratorCallback: '=removeCollaboratorCallback'
		},
		link: function(scope, element, attrs) {
			var self = this;

			self.init = function() {
				self.loggedInUser = AuthService.userInfo();
				self.service = (scope.itemType.toLowerCase() === 'campaign') ? CampaignService : ContentService;
			};

			self.refreshCollaborators = function () {
				scope.collaborators = self.service.queryCollaborators(self.loggedInUser.account.id, scope.itemId, null, {
					success: function (r) {
					},
					error: function (r) {
						launch.utils.handleAjaxErrorResponse(r, NotificationService);
					}
				});
			};

			self.validateScope = function() {
				if (launch.utils.isBlank(scope.itemType) || (scope.itemType.toLowerCase() !== 'content' && scope.itemType.toLowerCase() !== 'campaign')) {
					NotificationService.error('Error!!', 'Cannot add a colloborator to a ' + scope.itemType + '!');
					return false;
				}

				if (launch.utils.isBlank(scope.itemId) || isNaN(scope.itemId)) {
					NotificationService.error('Error!!', 'Invalid ' + scope.itemType + ' ID.');
					return false;
				}

				return true;
			};

			scope.collaborators = null;
			scope.newCollaborator = 0;

			scope.addCollaborator = function () {
				if (!self.validateScope()) {
					return;
				}

				scope.collaborators = self.service.insertCollaborator(self.loggedInUser.account.id, scope.itemId, parseInt(scope.newCollaborator), {
					success: function (r) {
						if ($.isFunction(scope.addCollaboratorCallback)) {
							scope.addCollaboratorCallback();
						}
					},
					error: function(r) {
						launch.utils.handleAjaxErrorResponse(r, NotificationService);
					}
				});

				scope.newCollaborator = 0;
			};

			scope.removeCollaborator = function (collaborator) {
				if (!self.validateScope()) {
					return;
				}

				scope.collaborators = self.service.deleteCollaborator(self.loggedInUser.account.id, parseInt(scope.itemId), collaborator.id, {
					success: function(r) {
						if ($.isFunction(scope.removeCollaboratorCallback)) {
							scope.removeCollaboratorCallback(collaborator);
						}
					},
					error: function (r) {
						launch.utils.handleAjaxErrorResponse(r, NotificationService);
					}
				});
			};

			self.init();

			scope.$watch('itemId', function() {
				if (!launch.utils.isBlank(scope.itemId)) {
					self.refreshCollaborators();
				}
			});
		}
	};
});