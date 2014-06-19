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

  var Rating = $resource('/api/uploads/:id/rating', { id: '@id' });

  return {
    Libraries: Libraries,
    Uploads: Uploads,
    Rating: Rating
  };
});