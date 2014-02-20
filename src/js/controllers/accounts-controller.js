
launch.module.controller('AccountsController', ['$scope', '$resource', function($scope, $resource) {
  $scope.title = 'Test api accounts';
  // Test resource usage
  var Account = $resource('/api/account');
  $scope.accounts = Account.query();
}]);
