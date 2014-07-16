launch.module.directive('launchButton', function(ContentService, AuthService, NotificationService) {
	var self = this;

	self.loggedInUser = null;

	self.ajaxHandler = {
		success: function (r) {

		},
		error: function (r) {
			launch.utils.handleAjaxErrorResponse(r, NotificationService);
		}
	};

	self.init = function() {
		self.loggedInUser = AuthService.userInfo();
	};

	var link = function (scope, element, attrs) {
		scope.selectedConnections = [];

		scope.launchContent = function (connection) {
			ContentService.launch(self.loggedInUser.account.id, scope.content.id, connection.id, self.ajaxHandler);
		};

		scope.toggleSelectedConnections = function (connection, e) {
			var checkbox = $(e.currentTarget);

			if (checkbox.is(':checked')) {
				if ($.grep(scope.selectedConnections, function (c) { return c.id === connection.id; }).length === 0) {
					scope.selectedConnections.push(connection);
				}
			} else {
				scope.selectedConnections = $.grep(scope.selectedConnections, function (c) {
					return c.id !== connection.id;
				});
			}

			e.stopImmediatePropagation();
		};

		scope.launchSelected = function () {
			if (!$.isArray(scope.selectedConnections) || scope.selectedConnections.length === 0) {
				NotificationService.error('Error!', 'Please select one or more connections to which to launch the content.');
				return;
			}

			$.each(scope.selectedConnections, function (i, c) {
				scope.launchContent(c);
			});

			scope.selectedConnections = [];
		};

		scope.connectionIsSupported = function (connection) {
			return launch.utils.connectionIsSupportsContentType(connection, scope.content);
		};
	};

	init();

	return {
		link: link,
		scope: {
			content: '=content',
			contentConnections: '=contentConnections'
		},
		templateUrl: '/assets/views/launch-button.html'
	};
});