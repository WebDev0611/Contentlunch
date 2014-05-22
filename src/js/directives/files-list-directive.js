﻿launch.module.directive('filesList', function($modal, $window, $location, AuthService, NotificationService) {
	var link = function(scope, element, attrs) {
		var self = this;

		self.loggedInUser = null;

		self.init = function() {
			self.loggedInUser = AuthService.userInfo();

			$(element).addClass('files-list');
		};

		scope.showFilesList = false;
		scope.isSaving = false;
		scope.percentComplete = 0;
		scope.isCollapsed = false;
		scope.fileCount = null;

		scope.uploadAttachment = function (files, form, control) {
			if ($.isArray(files) && files.length !== 1) {
				NotificationService.error('Invalid File!', 'Please make sure to select only one file for upload at a time.');
				$(control).replaceWith($(control).clone(true, true));
				return;
			}

			var file = $.isArray(files) ? files[0] : files;

		};

		scope.$watch('filesList', function () {
			if (!$.isArray(scope.filesList)) {
				scope.filesList = [];
			}

			scope.showFilesList = (scope.filesList.length > 0);

			if (scope.filesList.length === 1) {
				scope.fileCount = '(1 file)';
			} else {
				scope.fileCount = '(' + scope.filesList.length + ' files)';
			}
		});

		self.init();
	};

	return {
		link: link,
		scope: {
			afterSaveSuccess: '=afterSaveSuccess',
			filesList: '=filesList',
			isDisabled: '=isDisabled'
		},
		templateUrl: '/assets/views/files-list.html'
	}
});