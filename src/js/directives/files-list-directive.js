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
		scope.percentComplete = 0;

		scope.getUserInfo = function (userId) {
			var user = launch.utils.getUserById(scope.users, userId);

			return (!!user) ? user.formatName() : null;
		};

		scope.getUserImage = function (userId) {
			var user = launch.utils.getUserById(scope.users, userId);

			return (!!user) ? user.imageUrl() : null;
		};

		scope.showDownloadFile = function(file) {
			if (!!file) {
				if (file.isImage() || file.isVideo() || file.isAudio()) {
					return true;
				}
			}

			return false;
		};

		scope.viewFile = function(file) {
			if (!file || launch.utils.isBlank(file.path)) {
				return;
			}

			if (file.isImage() || file.isVideo() || file.isAudio()) {
				$modal.open({
					templateUrl: '/assets/views/dialogs/view-file-dialog.html',
					size: 'lg',
					controller: [
						'$scope', '$modalInstance', function (scp, instance) {
							scp.title = file.fileName;
							scp.path = file.path;
							scp.mimeType = file.mimeType;
							scp.isImage = file.isImage();
							scp.isVideo = file.isVideo();
							scp.isAudio = file.isAudio();

							scp.ok = function () {
								instance.dismiss('cancel');
							};
						}
					]
				});
			} else {
				$window.open(file.path, 'view_file_' + file.id);
			}

		};

		scope.downloadFile = function (file, e) {
			window.open('/api/uploads/' + file.id + '/download');

			e.stopImmediatePropagation();
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
								msg += 'Please enter a Content Title.\n';
							} else if (scp.description.length > 60) {
								msg += 'Please limit the Content Title to 60 characters or less.\n';
							}

							if (!launch.utils.isBlank(msg)) {
								NotificationService.error('Error!', 'Please fix the following:\n\n' + msg);
								return;
							}

							scope.isSaving = true;

							AccountService.addFile(self.loggedInUser.account.id, scp.file, scp.description, {
								success: function (r) {
									scope.isSaving = false;
									scope.filesList.push(r);

									scope.fileCount = scope.filesList.length;
									scope.showFilesList = true;

									if ($.isFunction(scope.afterAddFileSuccess)) {
										scope.afterAddFileSuccess(r);
									}

									scp.description = null;
									scp.file = null;
									scp.fileType = null;

									instance.close();
								},
								error: function (r) {
									scope.isSaving = false;
									launch.utils.handleAjaxErrorResponse(r, NotificationService);
								},
								progress: function(evt) {
									scope.percentComplete = parseInt(100.0 * evt.loaded / evt.total);
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

		scope.deleteAttachment = function (uploadFile, e) {
			$modal.open({
				templateUrl: 'confirm.html',
				controller: [
					'$scope', '$modalInstance', function(scp, instance) {
						scp.message = 'Are you sure you want to delete this file?';
						scp.okButtonText = 'Delete';
						scp.cancelButtonText = 'Cancel';
						scp.onOk = function () {
							AccountService.deleteFile(self.loggedInUser.account.id, uploadFile.id, {
								success: function (r) {
									var newList = $.grep(scope.filesList, function (f) { return f.id !== uploadFile.id; });

									scope.filesList = newList;

									if ($.isFunction(scope.afterRemoveFileSuccess)) {
										scope.afterRemoveFileSuccess(r);
									}
								},
								error: function(r) {
									launch.utils.handleAjaxErrorResponse(r, NotificationService);
								}
							});

							instance.close();
						};
						scp.onCancel = function () {
							instance.dismiss('cancel');
						};
					}
				]
			});

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
			afterAddFileSuccess: '=afterAddFileSuccess',
			afterRemoveFileSuccess: '=afterRemoveFileSuccess',
			filesList: '=filesList',
			isDisabled: '=isDisabled',
			users: '=users'
		},
		templateUrl: '/assets/views/directives/files-list.html'
	}
});
