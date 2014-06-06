launch.module.controller('ConsultAdminLibraryController', function ($scope, $modal, $route, LibraryService) {

  $scope.files = [];

  LibraryService.Api.query({}, function (response) {
    $scope.files = response[0].uploads;
  });

  $scope.addFile = function () {

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
          console.log(files, form, control);
          $scope.file = $.isArray(files) ? files[0] : files;
          $scope.fileName = $scope.file.name;
          $scope.fileType = launch.utils.getFileTypeCssClass($scope.file.name.substring($scope.file.name.lastIndexOf('.') + 1));
          console.log($scope.file);
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
          }).success(function () {
            $window.location.reload();
          });
        };
      }
    })
  };

});