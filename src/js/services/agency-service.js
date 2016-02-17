launch.module.factory('AgencyService', ["$resource", function($resource) {
    var self = this;

    self.clients = [];

    var clientResource = $resource('/api/agency/:accountId/client/:id', { id: '@id' }, {
        get: { method: 'GET'},
        query: { method: 'GET', isArray: true},
        update: { method: 'PUT'},
        insert: { method: 'POST'},
        delete: { method: 'DELETE'}
    });


    return {
        clients: self.clients,
        loadClients: function(accountId) {
            self.clients = clientResource.query({accountId:accountId});
            return self.clients;
        },
        addClient: function(accountId, client) {
            return clientResource.insert({accountId:accountId}, client);
        },
        removeClient: function(accountId, client) {
            return clientResource.delete({accountId:accountId}, client);

        },
        updateClient: function(accountId, client) {
            return clientResource.update({accountId:accountId}, client);
        }

    };
}]);