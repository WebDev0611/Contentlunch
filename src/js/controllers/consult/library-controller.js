launch.module.controller('ConsultLibraryController', function ($scope, $modal, LibraryService) {

  $scope.folders = [];
  $scope.files = [];
  $scope.selectedFolder = null;

  var self = $scope;

  $scope.documentTypes = [];
  $scope.documentUploaders = [];

  $scope.formatDocumentTypeItem = launch.utils.formatDocumentTypeItem;
  $scope.formatDocumentUploaderItem = launch.utils.formatDocumentUploaderItem;

  // Show the root folder of the library (folders and root files)
  $scope.showRoot = function () {
    $scope.selectedFolder = null;
    $scope.folders = [];
    $scope.files = [];
    // Get folders (global and account specific)
    LibraryService.Libraries.query({}, function (response) {
      $scope.folders = response;
    });
    // Get files in root folder (account specific)
    LibraryService.Uploads.query({ id: 'root' }, function (response) {
      $scope.files = response;
    });
  };
  // Show root by default
  $scope.showRoot();

  // Show a specific folder (just files)
  $scope.showFolder = function (folder) {
    $scope.selectedFolder = folder;
    $scope.folders = [];
    $scope.files = [];
    LibraryService.Uploads.query({ id: folder.id }, function (response) {
      $scope.files = response;
    });
  };

  // Upload a file
  $scope.addFile = function () {

    var parentScope = $scope;

    $modal.open({
      templateUrl: '/assets/views/consult/library-file-form.html',
      controller: function ($scope, $window, $modalInstance, $upload, LibraryService, NotificationService) {

        $scope.file = {};
        $scope.folders = [];
        $scope.uploadFile = new launch.UploadFile();

        $scope.fileFolders = [
          { key: 'root', name: '(Default to the root folder)' }
        ];

        // Get folders (only account specific)
        LibraryService.Libraries.query({ global: 0 }, function (response) {
          $scope.folders = response;
          // Add each folder to the select folder element
          angular.forEach(response, function (value, key) {
            $scope.fileFolders.push({
              key: value.id,
              name: value.name
            });
          });
          // Default to currently open folder
          $scope.uploadFile.folder = parentScope.selectedFolder.id;
        });

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
            url: '/api/library/' + $scope.uploadFile.folder + '/uploads',
            method: 'POST',
            data: data,
            file: $scope.file
          }).success(function (response) {
            // Reload view with folder that file was saved to
            var showFolder;
            angular.forEach($scope.folders, function (value, key) {
              if (value.id == $scope.uploadFile.folder) {
                showFolder = value;
              }
            });
            parentScope.showFolder(showFolder);
            NotificationService.success('Success!', 'File: ' + response.filename + ' saved.');
            $modalInstance.dismiss();
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
        $scope.folders = [];
        $scope.uploadFile = file;
        $scope.mode = 'edit';

        $scope.fileFolders = [
          { key: 'root', name: '(Default to the root folder)' }
        ];

        // Get folders (only account specific)
        LibraryService.Libraries.query({ global: 0 }, function (response) {
          $scope.folders = response;
          // Add each folder to the select folder element
          angular.forEach(response, function (value, key) {
            $scope.fileFolders.push({
              key: value.id,
              name: value.name
            });
          });
          // Default to currently open folder
          $scope.uploadFile.folder = parentScope.selectedFolder.id;
        });

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
            url: '/api/library/' + $scope.uploadFile.folder + '/uploads/' + $scope.uploadFile.id + '?description=' + $scope.uploadFile.description +'&tags='+ $scope.uploadFile.tags,
            method: 'PUT'
            //data: data,
            //file: $scope.file
          }).success(function (response) {
            // Reload view with folder that file was saved to
            var showFolder;
            angular.forEach($scope.folders, function (value, key) {
              if (value.id == $scope.uploadFile.folder) {
                showFolder = value;
              }
            });
            parentScope.showFolder(showFolder);
            NotificationService.success('Success!', 'File: ' + response.filename + ' saved.');
            $modalInstance.dismiss();
          });
        };

        // Delete file
        $scope.delete = function () {
          LibraryService.Uploads.delete({ id: parentScope.selectedFolder.id, uploadid: $scope.uploadFile.id }, function (response) {
            parentScope.showFolder(parentScope.selectedFolder);
            $modalInstance.dismiss();
            NotificationService.success('Success!', 'File: ' + $scope.uploadFile.fileName + ' deleted');
          }, function (response) {
            NotificationService.error('Error!', 'Unable to delete file');
          });
        };
      }
    });
  };

  // Add a folder
  $scope.addFolder = function () {

    var parentScope = $scope;

    $modal.open({
      templateUrl: '/assets/views/consult/library-folder-form.html',
      controller: function ($scope, $window, $modalInstance, LibraryService, NotificationService) {
        $scope.folder = {};

        $scope.cancel = function () {
          $modalInstance.dismiss('cancel');
        };

        $scope.ok = function () {
          LibraryService.Libraries.save($scope.folder, function (response) {
            parentScope.showFolder(response);
            $modalInstance.dismiss();
          }, function (response) {
            NotificationService.error('Error!', 'Please fix the following problems:\n\n' + response.errors.join('\n'));
          });
        };
      }
    });

  };

  $scope.canEditFolder = function (folder) {
    if (folder.global == '1') {
      return false;
    }
    return true;
  };

  $scope.canEditFile = function (file) {
    if ($scope.selectedFolder.global == '0') {
      return true;
    }
    return false;
  };

  $scope.search = {
      searchTerm: null,
      searchTermMinLength: 1,
      documentTypes: [],
      documentUploaders: [],
      changeSearchTerm: function() {
        if (launch.utils.isBlank($scope.search.searchTerm) || $scope.search.searchTerm.length >= $scope.search.searchTermMinLength) {
          $scope.search.applyFilter();
        }
      },
      applyFilter: function(reset) {
        $scope.filteredFiles = $filter('filter')($scope.files, function(file) {

          if ($.isArray($scope.search.documentTypes) && $scope.search.documentTypes.length > 0) {
            if ($.inArray(content.documentType.name, $scope.search.documentTypes) < 0) {
              return false;
            }
          }

          if ($.isArray($scope.search.documentUploaders) && $scope.search.documentUploaders.length > 0) {
            return ($.grep($scope.search.documentUploaders, function(uid) { return parseInt(uid) === file.user.id; }).length > 0);
          }

          return (launch.utils.isBlank($scope.search.searchTerm) ? true : content.matchSearchTerm($scope.search.searchTerm));
        });

        if (reset === true) {
          $scope.pagination.currentPage = 1;
        }

        $scope.pagination.totalItems = $scope.filteredContent.length;
        $scope.pagination.groupToPages();
      },
      clearFilter: function() {
        this.searchTerm = null;
        this.contentTypes = null;
        this.milestones = null;
        this.buyingStages = null;
        this.campaigns = null;
        this.users = null;

        this.applyFilter();
      }
    };

});