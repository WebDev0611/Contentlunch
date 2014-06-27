
launch.module.factory('ConferenceService', function ($resource) {
  
  var Conferences = $resource('/api/account/:account_id/conferences/:id', { account_id: '@account_id', id: '@id' }, {
    update: { method: 'PUT' }
  });

  return {
    Conferences: Conferences
  };

});