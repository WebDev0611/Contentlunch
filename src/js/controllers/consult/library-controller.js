launch.module.controller('ConsultLibraryController', function ($scope, $modal, LibraryService, AccountService, AuthService, UserService) {

  // Data from server
  $scope.data = [];
  // Folders shown in current view
  $scope.folders = [];
  // Files shown in current view
  $scope.files = [];
  // Currently selected folder
  $scope.selectedFolder = null; 

  var self = $scope;

  $scope.documentTypes = [];
  $scope.documentUploaders = [];

  $scope.formatDocumentTypeItem = launch.utils.formatDocumentTypeItem;
  $scope.formatDocumentUploaderItem = launch.utils.formatDocumentUploaderItem;

  // Show a specific folder
  $scope.showFolder = function (id) {
    $scope.folders = [];
    $scope.files = [];
    $scope.selectedFolder = _.find($scope.data, function (folder) {
      return folder.id == id;
    });
    // Show uploads of current folder
    $scope.files = $scope.selectedFolder.uploads;
    // If root of library, show folders, but not root folder
    if (id == 'root') {
      $scope.folders = _.select($scope.data, function (folder) {
        return folder.id != 'root';
      });
    }
  };

  $scope.init = function (initFolder) {
    // Get all libraries and uploads
    LibraryService.Libraries.query({}, function (response) {
      $scope.data = response;
      // Show library
      $scope.showFolder(initFolder);
    });
  };
  // Default to showing root of library
  $scope.init('root');

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
            NotificationService.error('Error', "Please fix the following problems: \n" + response.errors.join("\n"));
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
            url: '/api/library/' + $scope.uploadFile.folder + '/uploads/' + $scope.uploadFile.id + '?description=' + $scope.uploadFile.description +'&tags='+ $scope.uploadFile.tags,
            method: 'PUT'
          }).success(function (response) {
            // Reload view with folder that file was saved to
            parentScope.init($scope.uploadFile.folder);
            NotificationService.success('Success!', 'File: ' + response.filename + ' saved.');
            $modalInstance.dismiss();
          }).error(function (response) {
            NotificationService.error('Error', "Please fix the following problems: \n" + response.errors.join("\n"));
          });
        };

        // Delete file
        $scope.delete = function () {
          LibraryService.Uploads.delete({ id: parentScope.selectedFolder.id, uploadid: $scope.uploadFile.id }, function (response) {
            // Reload view with folder that file was saved to
            parentScope.init($scope.uploadFile.folder);
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
            parentScope.init(response.id);
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
    return false;
    if ($scope.selectedFolder.global == '0') {
      return true;
    }
    return false;
  };

  $scope.search = {
    init: function () {
      // Get all users in account
      var account = AuthService.accountInfo();
      $scope.search.documentUploaderOptions = UserService.getForAccount(account.id, null, null, true);
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
        // Check search term against filename, description and tags
        if (  ! launch.utils.isBlank($scope.search.searchTerm) &&
              ! _.contains(file.filename, $scope.search.searchTerm) &&
              ! _.contains(file.description, $scope.search.searchTerm) &&
              ! _.contains(_.map(file.tags, function (tag) { return tag.tag; }).join(), $scope.search.searchTerm)) {
          return false;
        }
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
          return _.contains($scope.search.documentTypes, fileType);
        }
        // Check document uploader
        if ($.isArray($scope.search.documentUploaders) && $scope.search.documentUploaders.length > 0) {
          return _.contains($scope.search.documentUploaders, file.user.id);
        }
      });
    },
    clearFilter: function() {
      this.searchTerm = null;
      this.documentTypes = null;
      this.documentUploaders = null;
      this.applyFilter();
    }
  };
  $scope.search.init();

});