launch.module.factory('LibraryService', function ($resource) {

  var Libraries = $resource('/api/library/:id', { id: '@id' }, {
    update: {
      method: 'PUT'
    }
  });

  var Uploads = $resource('/api/library/:id/uploads/:uploadid', { id: '@id', uploadid: '@uploadid' }, {
    update: {
      method: 'PUT'
    }
  });

  return {
    Libraries: Libraries,
    Uploads: Uploads
  };
});