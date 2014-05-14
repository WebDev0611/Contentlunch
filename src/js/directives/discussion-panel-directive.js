launch.module.directive('discussionPanel', function($modal, $window, $location, AuthService, NotificationService, ContentService, CampaignService) {
	return {
		templateUrl: '/assets/views/discussion-panel.html',
		scope: {
			discussion: '=discussion',
			itemId: '=itemId',
			itemType: '=itemType',
			addCommentCallback: '=addCommentCallback'
		},
		link: function(scope, element, attrs) {
			var self = this;

			self.init = function() {
				self.loggedInUser = AuthService.userInfo();
			};

			scope.newComment = null;

			scope.addComment = function () {
				if (launch.utils.isBlank(scope.itemType) || (scope.itemType.toLowerCase() !== 'content' && scope.itemType.toLowerCase() !== 'campaign')) {
					NotificationService.error('Error!!', 'Cannot attach a comment to a ' + scope.itemType + '!');
					return;
				}

				if (launch.utils.isBlank(scope.itemId) || isNaN(scope.itemId)) {
					NotificationService.error('Error!!', 'Invalid ' + scope.itemType + ' ID.');
					return;
				}

				var service = (scope.itemType.toLowerCase() === 'campaign') ? CampaignService : ContentService;
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

				// TODO: INSTEAD OF SIMPLY ADDING THE COMMENT, POST TO THE API AND REFRESH THE DISCUSSION!!
				service.insertComment(self.loggedInUser.account.id, comment, {
					success: function(r) {
						scope.discussion = service.queryComments(self.loggedInUser.account.id, scope.itemId, {
							error: function (r1) {
								launch.utils.handleAjaxErrorResponse(r1, NotificationService);
							}
						});
					},
					error: function(r) {
						launch.utils.handleAjaxErrorResponse(r, NotificationService);
					}
				});

				scope.newComment = null;

				if ($.isFunction(scope.addCommentCallback)) {
					scope.addCommentCallback(comment);
				}
			};

			self.init();
		}
	};
});