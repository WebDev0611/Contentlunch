launch.module.directive('collaboratorSidebar', function($modal, $window, $location, AuthService, NotificationService, ContentService, CampaignService) {
	return {
		templateUrl: '/assets/views/colloborator-sidebar.html',
		scope: {
			collaborators: '=collaborators',
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

			scope.newCollaborator = 0;

			scope.addCollaborator = function () {
				if (!self.validateScope()) {
					return;
				}

				// TODO: HOOK THIS UP TO THE API INSTEAD OF SIMPLY ADDING THE ITEM TO THE ARRAY!!
				var user = $.grep(scope.users, function(u, i) {
					return u.id === parseInt(scope.newCollaborator);
				});

				if (user.length === 1 && $.grep(scope.collaborators, function(c, i) { return c.id === user[0].id; }).length === 0) {
					var collaborator = {
						id: user[0].id,
						name: user[0].formatName(),
						image: user[0].imageUrl()
					};

					scope.collaborators.push(collaborator);

					if ($.isFunction(scope.addCollaboratorCallback)) {
						scope.addCollaboratorCallback();
					}
				}

				scope.newCollaborator = 0;
			};

			scope.removeCollaborator = function(collaborator) {
				if (!self.validateScope()) {
					return;
				}

				// TODO: HOOK THIS UP TO THE API INSTEAD OF SIMPLY REMOVING THE ITEM FROM THE ARRAY!!
				scope.collaborators = $.grep(scope.collaborators, function(c, i) {
					return c.id !== collaborator.id;
				});

				if ($.isFunction(scope.removeCollaboratorCallback)) {
					scope.removeCollaboratorCallback(collaborator);
				}
			};

			self.init();
		}
	};
});