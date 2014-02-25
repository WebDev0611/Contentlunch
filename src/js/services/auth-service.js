
launch.module.factory('AuthService', function ($resource) {
  return $resource('/api/auth/');
});
