launch.module.factory('LibraryService', function ($resource) {

  var resource = $resource('/api/library');

  return {
    Api: resource
  };
});