launch.module.directive('discussionPanel', function($modal, $window, $location, AuthService, NotificationService, ContentService, CampaignService) {
	return {
		templateUrl: '/assets/views/discussion-panel.html',
		scope: {
			itemId: '=itemId',
			itemType: '=itemType',
			addCommentCallback: '=addCommentCallback'
		},
		link: function(scope, element, attrs) {
			var self = this;

			self.init = function() {
				self.loggedInUser = AuthService.userInfo();

				scope.canAddComments = self.loggedInUser.hasPrivilege('collaborate_execute_feedback');
				scope.canViewComments = self.loggedInUser.hasPrivilege('collaborate_view_feedback_review');

				self.service = (scope.itemType.toLowerCase() === 'campaign') ? CampaignService : ContentService;
			};

			self.refreshComments = function() {
				scope.comments = self.service.queryComments(self.loggedInUser.account.id, scope.itemId, null, {
					success: function (r) {
						scope.comments = $.grep(scope.comments, function(c) {
							if (scope.canViewComments) {
								return true;
							}

							return (c.commentor.id === self.loggedInUser.id);
						});
					},
					error: function(r) {
						launch.utils.handleAjaxErrorResponse(r, NotificationService);
					}
				});
			};

			self.validateScope = function () {
				if (!scope.canAddComments) {
					NotificationService.error('Error!!', 'You are not authorized to add comments to this ' + scope.itemType + '. Please contact your administrator for more information.');
					return false;
				}

				if (launch.utils.isBlank(scope.itemType) || (scope.itemType.toLowerCase() !== 'content' && scope.itemType.toLowerCase() !== 'campaign')) {
					NotificationService.error('Error!!', 'Cannot add a comment to a ' + scope.itemType + '!');
					return false;
				}

				if (launch.utils.isBlank(scope.itemId) || isNaN(scope.itemId)) {
					NotificationService.error('Error!!', 'Invalid ' + scope.itemType + ' ID.');
					return false;
				}

				return true;
			};

			scope.canAddComments = false;
			scope.canViewComments = false;
			scope.comments = null;
			scope.newComment = null;

			scope.addComment = function () {
				if (!self.validateScope()) {
					return;
				}

				var comment = new launch.Comment();

				comment.id = null;
				comment.comment = scope.newComment;
				comment.itemId = scope.itemId;
				comment.commentDate = launch.utils.formatDateTime(new Date());
				comment.commentor = {
					id: self.loggedInUser.id,
					name: self.loggedInUser.displayName,
					image: self.loggedInUser.imageUrl()
				};

				var msg = launch.utils.validateAll(comment);

				if (!launch.utils.isBlank(msg)) {
					NotificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
					return;
				}

				self.service.insertComment(self.loggedInUser.account.id, comment, {
					success: function (r) {
						self.refreshComments();

						if ($.isFunction(scope.addCommentCallback)) {
							scope.addCommentCallback(comment);
						}
					},
					error: function(r) {
						launch.utils.handleAjaxErrorResponse(r, NotificationService);
					}
				});

				scope.newComment = null;
			};

			self.init();

			scope.$watch('itemId', function() {
				if (!launch.utils.isBlank(scope.itemId)) {
					self.refreshComments();
				}
			});
		}
	};
});