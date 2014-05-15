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
				self.service = (scope.itemType.toLowerCase() === 'campaign') ? CampaignService : ContentService;
			};

			self.refreshComments = function() {
				scope.comments = self.service.queryComments(self.loggedInUser.account.id, scope.itemId, null, {
					success: function(r) {
					},
					error: function(r) {
						launch.utils.handleAjaxErrorResponse(r, NotificationService);
					}
				});
			};

			self.validateScope = function () {
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