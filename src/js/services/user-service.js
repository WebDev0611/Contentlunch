
launch.module.factory('UserService', function ($resource) {
  return $resource('/api/user/');
});
