launch.module.directive('filePreview', function (ModelMapperService) {
  
  return {
    link: function (scope, element, attrs) {
      scope.launchFile = ModelMapperService.uploadFile.fromDto(scope.file);
      scope.user = ModelMapperService.user.fromDto(scope.file.user);
      console.log(scope.launchFile, scope.user, scope.user.imageUrl());

      scope.userImage = function () {
        return scope.user.image ? scope.user.image : '/images/user.svg';
      };
    },
    scope: {
      file: '=filePreview'
    },
    templateUrl: '/assets/views/directives/file-preview.html'
  };

});