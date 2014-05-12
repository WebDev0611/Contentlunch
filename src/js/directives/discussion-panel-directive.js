launch.module.directive('discussionPanel', function ($modal, $window, $location, AuthService, NotificationService) {
	return {
		templateUrl: '/assets/views/discussion-panel.html',
		scope: {
			discussion: '=discussion',
			itemId: '=itemId',
			itemType: '=itemType'
		},
		link: function (scope, element, attrs) {
			var self = this;

			self.init = function() { };

			if (launch.utils.isBlank(scope.itemId)) {
				throw 'Must specify itemId!';
			}

			if (launch.utils.isBlank(scope.itemType)) {
				throw 'Must specify itemType!';
			}

			var loggedInUser = AuthService.userInfo();

			scope.newComment = null;

			scope.addComment = function() {
				var comment = new launch.Comment();

				comment.id = null;
				comment.comment = scope.newComment;
				comment.commentDate = launch.utils.formatDateTime(new Date());
				comment.commentor = {
					id: loggedInUser.id,
					name: loggedInUser.displayName,
					image: loggedInUser.imageUrl()
				};

				var msg = launch.utils.validateAll(comment);

				if (!launch.utils.isBlank(msg)) {
					NotificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
					return;
				}

				// TODO: INSTEAD OF SIMPLY ADDING THE COMMENT, POST TO THE API AND REFRESH THE DISCUSSION!!
				scope.discussion.push(comment);

				scope.newComment = null;
			};

			self.init();
		}
	};
});
