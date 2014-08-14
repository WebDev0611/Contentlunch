﻿launch.module.directive('collaboratorSidebar', function($modal, $window, $location, AuthService, NotificationService, ContentService, CampaignService) {
	return {
		templateUrl: '/assets/views/directives/colloborator-sidebar.html',
		scope: {
			users: '=users',
			itemId: '=itemId',
			itemType: '=itemType',
			isDisabled: '=isDisabled',
			addCollaboratorCallback: '=addCollaboratorCallback',
			removeCollaboratorCallback: '=removeCollaboratorCallback',
			isCollaboratorFinished: '=isCollaboratorFinished'
		},
		link: function(scope, element, attrs) {
			var self = this;

			self.init = function() {
				self.loggedInUser = AuthService.userInfo();

				scope.canModifyCollaborators = self.loggedInUser.hasPrivilege('collaborate_execute_sendcontent');

				self.service = (scope.itemType.toLowerCase() === 'campaign') ? CampaignService : ContentService;

				scope.checkCollaborator = ($.isFunction(scope.isCollaboratorFinished)) ? scope.isCollaboratorFinished : function (c) { return false; };
				//scope.potentialCollaborators = $.grep(scope.users, function(u) { return u.active; });
			};

			self.refreshCollaborators = function () {
				scope.collaborators = self.service.queryCollaborators(self.loggedInUser.account.id, scope.itemId, null, {
					success: function (r) {
						scope.potentialCollaborators = $.grep(scope.users, function(u) {
							return u.active;
						});
					},
					error: function (r) {
						launch.utils.handleAjaxErrorResponse(r, NotificationService);
					}
				});
			};

			self.refreshTasks = function (callback) {
				if (scope.itemType.toLowerCase() === 'content') {
					var taskGroups = self.service.getTaskGroups(self.loggedInUser.account.id, scope.itemId, {
						success: function (r) {
							scope.tasks = [];

							$.each(taskGroups, function (i, tg) {
								$.merge(scope.tasks, tg.tasks);
							});

							if (!!callback && $.isFunction(callback.success)) {
								callback.success(r);
							}
						},
						error: function (r) {
							launch.utils.handleAjaxErrorResponse(r, NotificationService);
						}
					});
				} else {
					
				}
			};

			self.validateScope = function () {
				if (!scope.canModifyCollaborators || scope.isDisabled) {
					NotificationService.error('Error!!', 'You do not have sufficient privileges to add or remove collaborators. Please contact your administrator for more information.');
					return false;
				}

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

			scope.item = null;
			scope.tasks = null;
			scope.collaborators = null;
			scope.potentialCollaborators = null;
			scope.newCollaborator = 0;
			scope.canModifyCollaborators = false;

			scope.addCollaborator = function () {
				var handleAddCollaborator = function () {
					scope.newCollaborator = parseInt(scope.newCollaborator);

					if ($.isArray(scope.collaborators) && $.grep(scope.collaborators, function (c) { return c.id === scope.newCollaborator; }).length > 0) {
						scope.newCollaborator = 0;
						return;
					}

					scope.collaborators = self.service.insertCollaborator(self.loggedInUser.account.id, scope.itemId, parseInt(scope.newCollaborator), {
						success: function (r) {
							if ($.isFunction(scope.addCollaboratorCallback)) {
								scope.addCollaboratorCallback(r);
							}
						},
						error: function (r) {
							launch.utils.handleAjaxErrorResponse(r, NotificationService);
						}
					});

					scope.newCollaborator = 0;
				};

				if (scope.itemType.toLowerCase() === 'content') {
					self.refreshTasks({
						success: function (r) {
							if (!self.validateScope()) {
								scope.newCollaborator = 0;
								return;
							}

							handleAddCollaborator();
						}
					});
				} else {
					handleAddCollaborator();
				}
			};

			scope.removeCollaborator = function(collaborator) {
				var handleRemoveCollaborator = function() {
					if ($.isArray(scope.tasks) && $.grep(scope.tasks, function(t) { return (!t.isComplete && t.userId === collaborator.id); }).length > 0) {
						NotificationService.error('Error!!', 'There are tasks assigned to ' + collaborator.formatName() + '. You cannot delete a collaobrator that has been assigned tasks.');
						return;
					}

					scope.collaborators = self.service.deleteCollaborator(self.loggedInUser.account.id, parseInt(scope.itemId), collaborator.id, {
						success: function(r) {
							if ($.isFunction(scope.removeCollaboratorCallback)) {
								scope.removeCollaboratorCallback(r);
							}
						},
						error: function(r) {
							launch.utils.handleAjaxErrorResponse(r, NotificationService);
						}
					});
				};

				if (scope.itemType.toLowerCase() === 'content') {
					self.refreshTasks({
						success: function(r) {
							if (!self.validateScope()) {
								return;
							}

							handleRemoveCollaborator();
						}
					});
				} else {
					handleRemoveCollaborator();
				}
			};

			scope.userIsCollaborator = function(collaborator) {
				return (collaborator.id === self.loggedInUser.id);
			};

			self.init();

			scope.$watch('itemId', function() {
				if (!launch.utils.isBlank(scope.itemId)) {
					self.refreshCollaborators();
					self.refreshTasks();
				}
			});
		}
	};
});