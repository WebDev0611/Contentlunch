launch.module.factory('ModuleService', function ($resource) {

  var resource = $resource('/api/modules', {
    query: {
      method: 'GET',
      isArray: true
    }
  });

  return {
    modules: resource
  };
});