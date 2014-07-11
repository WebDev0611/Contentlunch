launch.module.directive('discussionPanel', function() {
	return {
		templateUrl: '/assets/views/discussion-panel.html',
		scope: {
			comments: '=comments',
			addCommentCallback: '=addCommentCallback'
		},
		link: function(scope, element, attrs) {
			scope.newComment = null;

			scope.addComment = function () {
				if ($.isFunction(scope.addCommentCallback)) {
					scope.addCommentCallback(scope.newComment);
				}

				scope.newComment = null;
			};

			scope.formatDate = launch.utils.formatDate;
		}
	};
});