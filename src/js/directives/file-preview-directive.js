launch.module.directive('filePreview', function (ModelMapperService) {
  
  return {
    controller: function ($scope) {
      $scope.fileType = launch.utils.mediaTypeMap($scope.file.media_type, $scope.file.extension);
      $scope.iconClass = launch.utils.getFileTypeCssClass($scope.fileType);
      $scope.rating = {
        rate: ( !! $scope.file.ratings[0] ? $scope.file.ratings[0].rating : 0),
        max: 5,
        userRate: ( !! $scope.file.user_rating[0] ? $scope.file.user_rating[0].rating : null),
        isReadonly: false,
        hover: function (value) {
          $scope.overStar = value;
        },
        tooltip: function () {
          var text = 'Rating: ' + parseFloat($scope.rating.rate).toFixed(1);
          if ($scope.rating.userRate) {
            text += '<br />You Rated: ' + $scope.rating.userRate;
          }
          return text;
        }
      };
    },
    link: function (scope, element, attrs) {
      scope.launchFile = ModelMapperService.uploadFile.fromDto(scope.file);
      scope.user = ModelMapperService.user.fromDto(scope.file.user);
      scope.views = !! scope.file.views[0] ? scope.file.views[0].total : 0;

      scope.userImage = function () {
        return scope.user.image ? scope.user.image : '/images/user.svg';
      };

    },
    scope: {
      file: '=filePreview',
      editFile: '=editFile',
      canEditFile: '=canEditFile',
      rateFile: '=rateFile'
    },
    templateUrl: '/assets/views/directives/file-preview.html'
  };

});