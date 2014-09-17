launch.module.directive('brainstormList', function($modal, $window, $location, AuthService, AccountService, NotificationService) {
	var link = function(scope, element, attrs) {
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

		scope.showScheduleBrainstorm = function (brainstorm) {
			var item = (scope.type === 'campaign') ? scope.campaign : scope.content;

			if (!brainstorm) {
				brainstorm = AccountService.getNewBrainstorm(self.loggedInUser.id, self.loggedInUser.account.id, scope.type, item.id);
			}

			$modal.open({
				templateUrl: '/assets/views/content/concept-brainstorm-modal.html',
				controller: [
					'$scope', '$modalInstance', function (scp, instance) {
						scp.brainstorm = brainstorm;
						scp.brainstorm.date = moment(scp.brainstorm.date).format();
                        scp.brainstorm.agenda = scp.brainstorm.agenda.join("\n");

						scp.save = function () {
                            scp.brainstorm.agenda = scp.brainstorm.agenda.split("\n");
                            console.log(scp.brainstorm)
							AccountService.addBrainstorm(scp.brainstorm, {
								success: function (r) {
									NotificationService.success('Success!', 'Your Brainstorm Session has successfully been scheduled.');

									if ($.isFunction(scope.afterUpdate)) {
										scope.afterUpdate();
									}
								},
								error: self.ajaxHandler.error
							});
							instance.close();
						};
					}
				]
			});
		}

		scope.removeBrainstorm = function (brainstorm, e) {
			AccountService.removeBrainstorm(brainstorm, {
				success: function (r) {
					NotificationService.success('Success!', 'Your Brainstorm Session has successfully been removed.');

					if ($.isFunction(scope.afterUpdate)) {
						scope.afterUpdate();
					}
				},
				error: self.ajaxHandler.error
			});

			e.stopImmediatePropagation();
		}

		self.init();
	};

	return {
		link: link,
		scope: {
			brainstorms: '=brainstorms',
			content: '=content',
			campaign: '=campaign',
			type: '=type',
			afterUpdate: '=afterUpdate'
		},
		templateUrl: '/assets/views/directives/brainstorm-list.html'
	};
});