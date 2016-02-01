launch.module.controller('ConsultAdminLibraryController', function($scope, $modal, $route, LibraryService, NotificationService) {

	$scope.files = [];
	$scope.globalID = null;

	$scope.init = function() {
		$scope.isLoading = true;
		LibraryService.Libraries.query({ }, function(response) {
			$scope.isLoading = false;
			if (response[0]) {
				$scope.files = response[0].uploads;
				$scope.globalID = response[0].id;
			}
		});
	};

	$scope.init();

	// Rate a file
	$scope.rateFile = function(fileID, rating) {
		LibraryService.Rating.save({ id: fileID, rating: rating }, function(response) {
			NotificationService.success('Success', 'Rating saved');
		});
	};

	$scope.addFile = function() {
		$modal.open({
			backdrop: 'static',
			templateUrl: '/assets/views/consult/library-file-form.html',
			controller: [
				'$scope', '$modalInstance', '$window', '$upload', function(scope, $modalInstance, $window, $upload) {

					scope.file = { };
					scope.uploadFile = new launch.UploadFile();
					scope.disableFolderSelect = true;

					scope.fileFolders = [
						{ key: '0', name: '(Default to the root folder)' }
					];

					scope.uploadFile.folder = '0';

					// User clicked browse and staged file for upload
					scope.addFile = function(files, form, control) {
						scope.file = $.isArray(files) ? files[0] : files;
						scope.fileName = scope.file.name;
						scope.fileType = launch.utils.getFileTypeCssClass(scope.file.name.substring(scope.file.name.lastIndexOf('.') + 1));
					};

					// Close modal
					scope.cancel = function() {
						$modalInstance.dismiss('cancel');
					};

					// Save file
					scope.ok = function() {
						$scope.isSaving = true;
						var data = {
							description: scope.uploadFile.description,
							tags: scope.uploadFile.tags
						};
						$upload.upload({
							url: '/api/library/' + $scope.globalID + '/uploads',
							method: 'POST',
							data: data,
							file: scope.file
						}).success(function(response) {
							$modalInstance.dismiss();
							NotificationService.success('Success!', 'File: ' + response.filename + ' added');
							$scope.init();
						}).progress(function(e) {
							$scope.percentComplete = parseInt(100.0 * e.loaded / e.total);
						}).then(function() {
							$scope.isSaving = false;
						});
					};
				}
			]
		});
	};

	// Edit a file
	$scope.editFile = function(file) {
		$modal.open({
			backdrop: 'static',
			templateUrl: '/assets/views/consult/library-file-form.html',
			controller: [
				'$scope', '$modalInstance', '$window', '$upload', 'LibraryService', 'NotificationService',
				function(scope, $modalInstance, $window, $upload, LibraryService, NotificationService) {
					scope.file = { };
					scope.uploadFile = file;
					scope.fileName = file.fileName;
					scope.disableFolderSelect = true;

					scope.fileType = launch.utils.getFileTypeCssClass(file.fileName.substring(file.fileName.lastIndexOf('.') + 1));

					scope.mode = 'edit';

					if ($.isArray(scope.uploadFile.tags)) {
						scope.uploadFile.tags = _.map(scope.uploadFile.tags, function(tag) {
							return tag.tag;
						}).join();
					}

					scope.fileFolders = [
						{ key: '0', name: '(Default to the root folder)' }
					];

					scope.uploadFile.folder = '0';

					// User clicked browse and staged file for upload
					scope.addFile = function(files, form, control) {
						scope.file = $.isArray(files) ? files[0] : files;
						scope.fileName = scope.file.name;
						scope.fileType = launch.utils.getFileTypeCssClass(scope.file.name.substring(scope.file.name.lastIndexOf('.') + 1));
					};

					// Close modal
					scope.cancel = function() {
						$modalInstance.dismiss('cancel');
					};

					// Save file
					scope.ok = function() {
						$scope.isSaving = true;
						$upload.upload({
							url: '/api/library/' + scope.uploadFile.libraries[0].id + '/uploads/' + scope.uploadFile.id + '?description=' + scope.uploadFile.description + '&tags=' + scope.uploadFile.tags,
							method: 'PUT'
							//data: data,
							//file: scope.file
						}).success(function(response) {
							$modalInstance.dismiss();
							NotificationService.success('Success!', 'File: ' + scope.uploadFile.fileName + ' updated');
							$scope.init();
						}).error(function(response) {
							launch.utils.handleAjaxErrorResponse(response, NotificationService);
						}).progress(function(e) {
							$scope.percentComplete = parseInt(100.0 * e.loaded / e.total);
						}).then(function() {
							$scope.isSaving = false;
						});
					};

					// Delete file
					scope.delete = function() {
						scope.isDeleting = true;
						$modal.open({
							backdrop: 'static',
							templateUrl: 'confirm.html',
							controller: [
								'$scope', '$modalInstance', function(modalScope, instance) {
									modalScope.message = 'Are you sure you want to delete this file?';
									modalScope.okButtonText = 'Delete';
									modalScope.cancelButtonText = 'Cancel';
									modalScope.onOk = function() {
										$scope.isDeleting = true;
										LibraryService.Uploads.delete({ id: scope.uploadFile.libraries[0].id, uploadid: scope.uploadFile.id }, function(response) {
											$scope.isDeleting = false;
											NotificationService.success('Success!', 'File: ' + scope.uploadFile.fileName + ' deleted');
											$modalInstance.close();
											$scope.init();
										}, function(response) {
											$scope.isDeleting = false;
											launch.utils.handleAjaxErrorResponse(response, NotificationService);
										});
										instance.close();
									};
									modalScope.onCancel = function() {
										instance.dismiss('cancel');
									};
								}
							]
						});

					};
				}
			]
		});
	};

	$scope.canEditFile = function(file) {
		return true;
	};
});