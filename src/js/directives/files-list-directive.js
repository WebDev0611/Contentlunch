launch.module.directive('filesList', function($modal, $window, $location, AuthService, AccountService, NotificationService) {
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

			AccountService.addFile(self.loggedInUser.account.id, file, null, {
				success: function(r) {
					scope.filesList.push(r);

					scope.fileCount = scope.filesList.length;
					scope.showFilesList = true;

					if ($.isFunction(scope.afterSaveSuccess)) {
						scope.afterSaveSuccess(r);
					}
				},
				error: function(r) {
					launch.utils.handleAjaxErrorResponse(r, NotificationService);
				}
			});
		};

		scope.getUserInfo = function (userId) {
			var user = launch.utils.getUserById(scope.users, userId);

			return (!!user) ? user.formatName() : null;
		};

		scope.getUserImage = function (userId) {
			var user = launch.utils.getUserById(scope.users, userId);

			return (!!user) ? user.imageUrl() : null;
		};

		scope.downloadFile = function (uploadFile) {
			// TODO: DO WE DOWNLOAD OR VIEW THE FILE?
			NotificationService.info('WARNING!!', 'FILE VIEW/DOWNLOAD NOT YET IMPLEMENTED!');
		};

		scope.editAttachment = function (uploadFile, e) {
			if (!uploadFile) {
				uploadFile = new launch.UploadFile();
			}

			$modal.open({
				templateUrl: 'edit-attachment.html',
				controller: [
					'$scope', '$modalInstance', function (scp, instance) {
						scp.description = uploadFile.description;
						scp.file = null;
						scp.fileName = uploadFile.fileName;
						scp.fileType = launch.utils.getFileTypeCssClass(uploadFile.extension);

						scp.ok = function () {
							var msg = '';

							if (!scp.file) {
								msg += 'Please select a file to upload.\n';
							}

							if (launch.utils.isBlank(scp.description)) {
								msg += 'Please enter a description.\n';
							}

							if (!launch.utils.isBlank(msg)) {
								NotificationService.error('Error!', 'Please fix the following:\n\n' + msg);
								return;
							}

							AccountService.addFile(self.loggedInUser.account.id, scp.file, scp.description, {
								success: function (r) {
									scope.filesList.push(r);

									scope.fileCount = scope.filesList.length;
									scope.showFilesList = true;

									if ($.isFunction(scope.afterSaveSuccess)) {
										scope.afterSaveSuccess(r);
									}

									scp.description = null;
									scp.file = null;
									scp.fileType = null;

									instance.close();
								},
								error: function (r) {
									launch.utils.handleAjaxErrorResponse(r, NotificationService);
								}
							});
						};
						scp.cancel = function () {
							scp.description = null;
							scp.file = null;
							scp.fileType = null;

							instance.dismiss('cancel');
						};

						scp.getAttachment = function (files, form, control) {
							if ($.isArray(files) && files.length !== 1) {
								NotificationService.error('Invalid File!', 'Please make sure to select only one file for upload at a time.');
								$(control).replaceWith($(control).clone(true, true));
								return;
							}

							scp.file = $.isArray(files) ? files[0] : files;
							scp.fileName = scp.file.name;
							scp.fileType = launch.utils.getFileTypeCssClass(scp.file.name.substring(scp.file.name.lastIndexOf('.') + 1));
						}
					}
				]
			});

			e.stopImmediatePropagation();
		};

		scope.deleteAttachment = function(uploadFile, e) {
			// TODO: ADD DELETE ATTACHMENT CODE!!
			NotificationService.info('WARNING!!', 'DELETE FILE NOT YET IMPLEMENTED!');

			e.stopImmediatePropagation();
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
			isDisabled: '=isDisabled',
			users: '=users'
		},
		templateUrl: '/assets/views/files-list.html'
	}
});
