launch.module.factory('ConnectionService', function ($resource, ModelMapperService) {
	var providers = $resource('/api/connections', null, {
		get: { method: 'GET', isArray: true, transformResponse: ModelMapperService.connectionProvider.parseResponse }
	});

	var connections = $resource('/api/account/:accountId/connections/:id', { accountId: '@accountId', id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.connection.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.connection.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.connection.formatRequest, transformResponse: ModelMapperService.connection.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.connection.formatRequest, transformResponse: ModelMapperService.connection.parseResponse },
		delete: { method: 'DELETE' }
	});

	var actions = $resource('/api/account/:accountId/connections/:id/:action', { accountId: '@accountId', id: '@id', action: '@action' }, {
		get: { method: 'GET', isArray: true }
	});

	var createConnection = $resource('/api/account/:accountId/connections/create', { accountId: '@accountId' }, {
		insert: { method: 'GET', isArray: false }	
	});

	return {
		getProviders: function(providerType, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return providers.get({ type: providerType }, success, error);
		},

		queryContentConnections: function (accountId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return connections.query({ type: 'content', accountId: accountId }, success, error);
		},
		getContentConnection: function (accountId, id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return connections.get({ accountId: accountId, id: id }, success, error);
		},
		updateContentConnection: function (connection, callback) {
			console.log(connection);
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return connections.update({ accountId: connection.accountId, id: connection.id }, connection, success, error);
		},
		addContentConnection: function (connection, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return connections.insert({ accountId: accountId }, connection, success, error);
		},
		deleteContentConnection: function (connection, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return connections.delete({ accountId: connection.accountId, id: connection.id }, connection, success, error);
		},

		queryPromoteConnections: function (accountId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return connections.query({ type: 'promote', accountId: accountId }, success, error);
		},
		getPromoteConnection: function (accountId, id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return connections.get({ accountId: accountId, id: id }, success, error);
		},
		updatePromoteConnection: function (connection, callback) {
			console.log(connection);
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return connections.update({ accountId: connection.accountId, id: connection.id }, connection, success, error);
		},
		addPromoteConnection: function (connection, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return connections.insert({ accountId: accountId }, connection, success, error);
		},
		deletePromoteConnection: function (connection, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return connections.delete({ accountId: connection.accountId, id: connection.id }, connection, success, error);
		},

		querySeoConnections: function (accountId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return connections.query({ accountId: accountId }, success, error);
		},
		getSeoConnection: function (accountId, id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return connections.get({ accountId: accountId, id: id }, success, error);
		},
		getAuthors: function (accountId, id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return actions.get({ accountId: accountId, id: id, action: 'authors' }, success, error);
		},
		getTemplates: function (accountId, id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return actions.get({ accountId: accountId, id: id, action: 'templates' }, success, error);	
		},
		checkStatus: function (accountId, id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return actions.get({ accountId: accountId, id: id, action: 'status' }, success, error);
		},
		createConnection: function (accountId, params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			params.accountId = accountId;
			return createConnection.insert(params, success, error);
		}
	};
});
