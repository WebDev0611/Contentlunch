launch.module.directive('folderPreview', function (ModelMapperService, $modal, LibraryService, NotificationService) {
  
  return {
    link: function (scope, element, attrs) {
      
      scope.folder.updated = new Date(scope.folder.updated_at);
      scope.user = ModelMapperService.user.fromDto(scope.folder.user);
      scope.userImage = function () {
        return scope.user.image ? scope.user.image : '/images/user.svg';
      };

    },
    scope: {
      folder: '=folderPreview',
      showFolder: '=showFolder',
      canEditFolder: '=canEditFolder',
      editFolder: '=editFolder'
    },
    templateUrl: '/assets/views/directives/folder-preview.html'
  };

});