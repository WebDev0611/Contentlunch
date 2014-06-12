launch.module.controller('ConsultAdminLibraryController', function ($scope, $modal, $route, LibraryService, NotificationService) {

  $scope.files = [];

  $scope.init = function () {
    LibraryService.Uploads.query({ id: 'global' }, function (response) {
      $scope.files = response;
    });
  }
  $scope.init();

  $scope.addFile = function () {

    var parentScope = $scope;

    $modal.open({
      templateUrl: '/assets/views/consult/library-file-form.html',
      controller: function ($scope, $window, $modalInstance, $upload) {

        $scope.file = {};
        $scope.uploadFile = new launch.UploadFile();

        $scope.fileFolders = [
          { key: '0', name: '(Default to the root folder)' }
        ];

        $scope.uploadFile.folder = '0';

        // User clicked browse and staged file for upload
        $scope.addFile = function (files, form, control) {
          $scope.file = $.isArray(files) ? files[0] : files;
          $scope.fileName = $scope.file.name;
          $scope.fileType = launch.utils.getFileTypeCssClass($scope.file.name.substring($scope.file.name.lastIndexOf('.') + 1));
        };

        // Close modal
        $scope.cancel = function () {
          $modalInstance.dismiss('cancel');
        };

        // Save file
        $scope.ok = function () {
          var data = {
            description: $scope.uploadFile.description,
            tags: $scope.uploadFile.tags
          };
          $upload.upload({
            url: '/api/library/1/uploads',
            method: 'POST',
            data: data,
            file: $scope.file
          }).success(function (response) {
            $modalInstance.dismiss();
            NotificationService.success('Success!', 'File: ' + response.filename + ' added');
            parentScope.init();
          });
        };
      }
    });
  };

  // Edit a file
  $scope.editFile = function (file) {
    var parentScope = $scope;

    $modal.open({
      templateUrl: '/assets/views/consult/library-file-form.html',
      controller: function ($scope, $window, $modalInstance, $upload, LibraryService, NotificationService) {

        $scope.file = {};
        $scope.uploadFile = file;
        $scope.mode = 'edit';

        $scope.fileFolders = [
          { key: '0', name: '(Default to the root folder)' }
        ];

        $scope.uploadFile.folder = '0';

        // User clicked browse and staged file for upload
        $scope.addFile = function (files, form, control) {
          $scope.file = $.isArray(files) ? files[0] : files;
          $scope.fileName = $scope.file.name;
          $scope.fileType = launch.utils.getFileTypeCssClass($scope.file.name.substring($scope.file.name.lastIndexOf('.') + 1));
        };

        // Close modal
        $scope.cancel = function () {
          $modalInstance.dismiss('cancel');
        };

        // Save file
        $scope.ok = function () {
          $upload.upload({
            url: '/api/library/1/uploads/' + $scope.uploadFile.id + '?description=' + $scope.uploadFile.description +'&tags='+ $scope.uploadFile.tags,
            method: 'PUT'
            //data: data,
            //file: $scope.file
          }).success(function (response) {
            $modalInstance.dismiss();
            NotificationService.success('Success!', 'File: ' + $scope.uploadFile.fileName + ' updated');
            parentScope.init();
          });
        };

        // Delete file
        $scope.delete = function () {
          LibraryService.Uploads.delete({ id: 'global', uploadid: $scope.uploadFile.id }, function (response) {
            $modalInstance.dismiss();
            NotificationService.success('Success!', 'File: ' + $scope.uploadFile.fileName + ' deleted');
            parentScope.init();
          }, function (response) {
            NotificationService.error('Error!', 'Unable to delete file');
          });
        };
      }
    });
  };

});