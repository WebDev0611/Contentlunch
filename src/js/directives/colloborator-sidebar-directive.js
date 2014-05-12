launch.module.directive('collaboratorSidebar', function ($modal, $window, $location) {

	return {
		templateUrl: '/assets/views/colloborator-sidebar.html',
		scope: {
			collaborators: '=collaborators',
			users: '=users'
		},
		link: function(scope, element, attrs) {
			scope.newCollaborator = 0;

			scope.addCollaborator = function () {
				var user = $.grep(scope.users, function (u, i) {
					return u.id === parseInt(scope.newCollaborator);
				});

				if (user.length === 1) {
					if ($.grep(scope.collaborators, function (c, i) { return c.id === user[0].id; }).length === 0) {
						scope.collaborators.push({
							id: user[0].id,
							name: user[0].formatName(),
							image: user[0].imageUrl()
						});
					}
				}

				scope.newCollaborator = 0;
			};

			scope.removeCollaborator = function (collaborator) {
				scope.collaborators = $.grep(scope.collaborators, function (c, i) {
					return c.id !== collaborator.id;
				});
			};

		}
	};
});
