launch.module.directive('filePreview', function (ModelMapperService) {
  
  return {
    link: function (scope, element, attrs) {
      scope.launchFile = ModelMapperService.uploadFile.fromDto(scope.file);
      scope.user = ModelMapperService.user.fromDto(scope.file.user);

      scope.userImage = function () {
        return scope.user.image ? scope.user.image : '/images/user.svg';
      };

    },
    scope: {
      file: '=filePreview',
      editFile: '=editFile',
      canEditFile: '=canEditFile'
    },
    templateUrl: '/assets/views/directives/file-preview.html'
  };

});