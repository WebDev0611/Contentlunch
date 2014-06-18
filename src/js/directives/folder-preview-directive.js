launch.module.directive('folderPreview', function (ModelMapperService, $modal, LibraryService, NotificationService) {
  
  return {
    link: function (scope, element, attrs) {
      console.log(scope.folder);
      scope.folder.updated = new Date(scope.folder.updated_at);
      scope.user = ModelMapperService.user.fromDto(scope.folder.user);
      scope.userImage = function () {
        return scope.user.image ? scope.user.image : '/images/user.svg';
      };
      
      scope.editFolder = function () {
    
        var parentScope = scope;

        $modal.open({
          templateUrl: '/assets/views/consult/library-folder-form.html',
          controller: function ($scope, $window, $modalInstance, LibraryService, NotificationService) {
            $scope.folder = scope.folder;
            $scope.mode = 'edit';

            $scope.cancel = function () {
              $modalInstance.dismiss('cancel');
            };

            $scope.ok = function () {
              LibraryService.Libraries.update($scope.folder, function (response) {
                parentScope.showFolder(response);
                $modalInstance.dismiss();
              }, function (response) {
                NotificationService.error('Error!', 'Please fix the following problems:\n\n' + response.errors.join('\n'));
              });
            };

            $scope.delete = function () {
              LibraryService.Libraries.delete({ id: $scope.folder.id }, function (response) {
                parentScope.showRoot();
                $modalInstance.dismiss();
                NotificationService.success('Success!', 'Folder: ' + $scope.folder.name + ' deleted');
              }, function (response) {
                NotificationService.error('Error!', 'Unable to delete folder');
              });
            };
          }
        });

      };

    },
    scope: {
      folder: '=folderPreview',
      showFolder: '=showFolder',
      canEditFolder: '=canEditFolder'
    },
    templateUrl: '/assets/views/directives/folder-preview.html'
  };

});