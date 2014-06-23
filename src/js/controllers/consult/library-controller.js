launch.module.controller('ConsultLibraryController', function ($scope, $modal, LibraryService, AccountService, AuthService, UserService, NotificationService) {

  // Data from server
  $scope.data = [];
  // Folders shown in current view
  $scope.folders = [];
  // Files shown in current view
  $scope.files = [];
  // Currently selected folder
  $scope.selectedFolder = null;
  // If add folder button should show
  $scope.disableAddFolder = false;

  $scope.loggedInUser = null;

  var self = $scope;

  $scope.documentTypes = [];
  $scope.documentUploaders = [];

  $scope.formatDocumentTypeItem = launch.utils.formatDocumentTypeItem;
  $scope.formatDocumentUploaderItem = launch.utils.formatDocumentUploaderItem;

  // Show a specific folder
  $scope.showFolder = function (id) {
    $scope.search.clear();
    $scope.folders = [];
    $scope.files = [];
    $scope.selectedFolder = _.find($scope.data, function (folder) {
      return folder.id == id;
    });

    if (typeof $scope.selectedFolder.uploads != 'undefined') {
      $scope.files = $scope.selectedFolder.uploads;
    } else {
      $scope.files = [];
    }
    
    // If root of library, show folders, but not root folder
    if (id == 'root') {
      $scope.folders = _.select($scope.data, function (folder) {
        return folder.id != 'root';
      });
      $scope.disableAddFolder = false;
    } else {
      $scope.disableAddFolder = true;
    }
  };

  // Get available folders to save files to
  $scope.getFolderOptions = function () {
    var fileFolders = [
      { key: 'root', name: '(Default to the root folder)' }
    ];
    angular.forEach($scope.data, function (folder) {
      // Not global or root
      if (folder.id != 'root' && folder.global != '1') {
        fileFolders.push({
          key: folder.id,
          name: folder.name
        });
      }
    });
    return fileFolders;
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
        $scope.uploadFile.tags = '';
        $scope.disableFolderSelect = false;

        // Setup available folders to save file to
        $scope.fileFolders = parentScope.getFolderOptions();
        // Default to currently open folder
        if (parentScope.selectedFolder) {
          $scope.uploadFile.folder = parentScope.selectedFolder.id;
        }
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
            parentScope.init($scope.uploadFile.folder);
            NotificationService.success('Success!', 'File: ' + response.filename + ' saved.');
            $modalInstance.dismiss();
          }).error(function (response) {
            launch.utils.handleAjaxErrorResponse(response, NotificationService);
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
        $scope.disableFolderSelect = false;

        $scope.fileType = launch.utils.getFileTypeCssClass(file.fileName.substring(file.fileName.lastIndexOf('.') + 1));

        $scope.fileFolders = parentScope.getFolderOptions();
        // Default to currently open folder
        $scope.uploadFile.folder = parentScope.selectedFolder.id;

        if ($.isArray($scope.uploadFile.tags)) {
          $scope.uploadFile.tags = _.map($scope.uploadFile.tags, function (tag) {
            return tag.tag;
          }).join();
        }

        $scope.fileName = $scope.uploadFile.fileName;

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
            url: '/api/library/' + $scope.uploadFile.folder + '/uploads/' + file.id + '?description=' + $scope.uploadFile.description +'&tags='+ $scope.uploadFile.tags,
            method: 'PUT'
          }).success(function (response) {
            // Reload view with folder that file was saved to
            parentScope.init($scope.uploadFile.folder);
            NotificationService.success('Success!', 'File: ' + response.filename + ' saved.');
            $modalInstance.dismiss();
          }).error(function (response) {
            launch.utils.handleAjaxErrorResponse(response, NotificationService);
          });
        };

        // Delete file
        $scope.delete = function () {  

          $modal.open({
            templateUrl: 'confirm.html',
            controller: ['$scope', '$modalInstance', function (modalScope, instance) {
              modalScope.message = 'Are you sure you want to delete this file?';
              modalScope.okButtonText = 'Delete';
              modalScope.cancelButtonText = 'Cancel';
              modalScope.onOk = function () {
                if ( ! $scope.uploadFile.libraries[0]) {
                  libraryID = 'root';
                } else {
                  libraryID = $scope.uploadFile.libraries[0].id;
                }
                LibraryService.Uploads.delete({ id: libraryID, uploadid: $scope.uploadFile.id }, function (response) {
                  NotificationService.success('Success!', 'File: ' + $scope.uploadFile.fileName + ' deleted');
                  $modalInstance.close();
                  parentScope.init(libraryID);
                }, function (response) {
                  launch.utils.handleAjaxErrorResponse(response, NotificationService);
                });
                instance.close();
              };
              modalScope.onCancel = function () {
                instance.dismiss('cancel');
              };
            }]
          });

        };

      }
    });
  };

  // Rate a file
  $scope.rateFile = function (fileID, rating) {
    LibraryService.Rating.save({ id: fileID, rating: rating }, function (response) {
      NotificationService.success('Success', 'Rating saved');
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
            parentScope.init(response.id);
            $modalInstance.dismiss();
          }, function (response) {
            launch.utils.handleAjaxErrorResponse(response, NotificationService);
          });
        };
      }
    });

  };

  $scope.editFolder = function (folder) {
    
    var parentScope = $scope;

    $modal.open({
      templateUrl: '/assets/views/consult/library-folder-form.html',
      controller: function ($scope, $window, $modalInstance, LibraryService, NotificationService) {
        $scope.folder = folder;
        $scope.mode = 'edit';

        $scope.cancel = function () {
          $modalInstance.dismiss('cancel');
        };

        $scope.ok = function () {
          LibraryService.Libraries.update($scope.folder, function (response) {
            parentScope.init($scope.folder.id);
            $modalInstance.close();
          }, function (response) {
            launch.utils.handleAjaxErrorResponse(response, NotificationService);
          });
        };

        $scope.delete = function () {

          $modal.open({
            templateUrl: 'confirm.html',
            controller: ['$scope', '$modalInstance', function (modalScope, instance) {
              modalScope.message = 'Are you sure you want to delete this folder?';
              modalScope.okButtonText = 'Delete';
              modalScope.cancelButtonText = 'Cancel';
              modalScope.onOk = function () {
                LibraryService.Libraries.delete({ id: $scope.folder.id }, function (response) {
                  parentScope.init('root');
                  $modalInstance.close();
                  NotificationService.success('Success!', 'Folder: ' + $scope.folder.name + ' deleted');
                }, function (response) {
                  launch.utils.handleAjaxErrorResponse(response, NotificationService);
                });
                instance.close();
              };
              modalScope.onCancel = function () {
                instance.dismiss('cancel');
              };
            }]
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
    if (file.accountId) {
      return true;
    }
    return false;
  };

  $scope.search = {
    init: function () {
      // Get all users in account
      var account = AuthService.accountInfo();
      $scope.search.documentUploaderOptions = UserService.getForAccount(account.id, null, null, true);
      // Check for any user saved default filters
      if ($scope.loggedInUser.preferences.library) {
        prefs = $scope.loggedInUser.preferences.library;
        $scope.search.searchTerm = prefs.searchTerm;
        this.documentTypes = prefs.documentTypes;
        this.documentUploaders = prefs.documentUploaders;
        $scope.search.applyFilter();
      }
    },
    searchTerm: null,
    searchTermMinLength: 1,
    documentTypes: [],
    documentTypeOptions: [
      { key: 'audio', name: 'Audio'},
      { key: 'document', name: 'Document'},
      { key: 'folder', name: 'Folder'},
      { key: 'image', name: 'Images'},
      { key: 'pdf', name: 'PDF'},
      { key: 'ppt', name: 'Powerpoint'},
      { key: 'spreadsheet', name: 'Spreadsheet'},
      { key: 'video', name: 'Video'},
      { key: 'other', name: 'Other'}
    ],
    documentUploaders: [],
    documentUploaderOptions: [],
    activeSearch: function () {
      if (this.searchTerm || this.documentTypes.length || this.documentUploaders.length) {
        return true;
      }
      return false;
    },
    changeSearchTerm: function() {
      if (launch.utils.isBlank($scope.search.searchTerm) || $scope.search.searchTerm.length >= $scope.search.searchTermMinLength) {
        $scope.search.applyFilter();
      }
    },
    applyFilter: function(reset) {
      // When any search terms are present, don't show folders
      $scope.folders = [];
      $scope.selectedFolder = null;
      // Gather all uploads
      var allFiles = [];
      _.forEach($scope.data, function (folder) {
        allFiles = _.union(allFiles, folder.uploads);
      });
      // Filter by: search term, document type, document uploader
      $scope.files = _.filter(allFiles, function (file) {
        var match = true;
        // Check search term against filename, description and tags
        if (  ! launch.utils.isBlank($scope.search.searchTerm)) {
          var target = file.filename + ' ' + file.description + ' ' + _.map(file.tags, function (tag) { return tag.tag; }).join();
          if (target.toLowerCase().indexOf($scope.search.searchTerm.toLowerCase()) != -1) {
            match = true;
          } else {
            match = false;
          }
        }
        return match;
      });
      // Filter by: document type, document uploader
      $scope.files = _.filter($scope.files, function (file) {
        // Check document types against file (or filter)
        if ($.isArray($scope.search.documentTypes) && $scope.search.documentTypes.length > 0) {
          // Classify file type to match document types options
          // audio, document, image, pdf, ppt, spreadsheet, video, other
          var fileType = 'other';
          switch (file.media_type) {
            case 'text':
              fileType = 'document';
            break;
            case 'application':
              // Check extension
              switch (file.extension.toLowerCase()) {
                case 'pdf':
                  fileType = 'pdf';
                break;
                case 'pot':
                case 'potm':
                case 'potx':
                case 'pps':
                case 'ppsm':
                case 'ppsx':
                case 'ppt':
                case 'pptm':
                case 'pptx':
                  fileType = 'ppt';
                break;
                case '123':
                case 'accdb':
                case 'accde':
                case 'accdr':
                case 'accdt':
                case 'nb':
                case 'numbers':
                case 'ods':
                case 'ots':
                case 'sdc':
                case 'xl':
                case 'xlr':
                case 'xls':
                case 'xlsb':
                case 'xlsm':
                case 'xlsx':
                case 'xlt':
                case 'xltm':
                case 'xltx':
                case 'xlw':
                  fileType = 'spreadsheet';
                break;
                default:
                  fileType = 'document';
              }
            break;
            case 'audio':
              fileType = 'audio';
            break;
            case 'image':
              fileType = 'image';
            break;
            case 'video':
              fileType = 'video';
            break;
          }
          if ( ! _.contains($scope.search.documentTypes, fileType)) {
            return false;
          }
        }
        // Check document uploader (or filter)
        if ($.isArray($scope.search.documentUploaders) && $scope.search.documentUploaders.length > 0) {
          if ( ! _.contains($scope.search.documentUploaders, file.user.id)) {
            return false;
          }
        }
        return true;
      });
    },
    clear: function() {
      console.log('clear search');
      this.searchTerm = null;
      this.documentTypes = [];
      this.documentUploaders = [];
      this.applyFilter();
    },
    clearFilter: function () {
      this.clear();
      $scope.showFolder('root');
    },
    saveFilter: function() {
      UserService.savePreferences($scope.loggedInUser.id, 'library', {
        searchTerm: $scope.search.searchTerm,
        documentTypes: $scope.search.documentTypes,
        documentUploaders: $scope.search.documentUploaders
      }, {
        success: function () {
          NotificationService.success('Success', 'Library default filters saved.');
        }
      });
    }
  };
  $scope.init = function (initFolder) {
    // Get all libraries and uploads
    LibraryService.Libraries.query({}, function (response) {
      $scope.data = response;
      // Show library
      $scope.showFolder(initFolder);
      if ( ! $scope.initialized) {
        // Need the most up to date record of the user on the server
        AuthService.fetchCurrentUser({
          success: function (user) {
            $scope.loggedInUser = user;
            $scope.search.init();
            $scope.initialized = true;
          }
        });
      }
    });
  };
  // Default to showing root of library
  $scope.initialized = false;
  $scope.init('root');
});