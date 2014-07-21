launch.module.factory('ConnectionService', function ($resource, ModelMapperService) {
	var providers = $resource('/api/connections', null, {
		get: { method: 'GET', isArray: true }
	});

	var contentConnections = $resource('/api/account/:accountId/connections/:id?type=content', { accountId: '@accountId', id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.contentConnection.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.contentConnection.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.contentConnection.formatRequest, transformResponse: ModelMapperService.contentConnection.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.contentConnection.formatRequest, transformResponse: ModelMapperService.contentConnection.parseResponse },
		delete: { method: 'DELETE' }
	});

	var promoteConnections = $resource('/api/account/:accountId/connections/:id?type=promote', { accountId: '@accountId', id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.promoteConnection.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.promoteConnection.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.promoteConnection.formatRequest, transformResponse: ModelMapperService.promoteConnection.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.promoteConnection.formatRequest, transformResponse: ModelMapperService.promoteConnection.parseResponse },
		delete: { method: 'DELETE' }
	});

	var seoConnections = $resource('/api/account/:accountId/connections/:id?type=seo', { accountId: '@accountId', id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.seoConnection.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.seoConnection.parseResponse }
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

			return contentConnections.query({ accountId: accountId }, success, error);
		},
		getContentConnection: function (accountId, id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentConnections.get({ accountId: accountId, id: id }, success, error);
		},
		updateContentConnection: function (connection, callback) {
			console.log(connection);
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentConnections.update({ accountId: connection.accountId, id: connection.id }, connection, success, error);
		},
		addContentConnection: function (connection, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentConnections.insert({ accountId: accountId }, connection, success, error);
		},
		deleteContentConnection: function (connection, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentConnections.delete({ accountId: connection.accountId, id: connection.id }, connection, success, error);
		},

		queryPromoteConnections: function (accountId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return promoteConnections.query({ accountId: accountId }, success, error);
		},
		getPromoteConnection: function (accountId, id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return promoteConnections.get({ accountId: accountId, id: id }, success, error);
		},
		updatePromoteConnection: function (connection, callback) {
			console.log(connection);
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return promoteConnections.update({ accountId: connection.accountId, id: connection.id }, connection, success, error);
		},
		addPromoteConnection: function (connection, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return promoteConnections.insert({ accountId: accountId }, connection, success, error);
		},
		deletePromoteConnection: function (connection, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return promoteConnections.delete({ accountId: connection.accountId, id: connection.id }, connection, success, error);
		},

		querySeoConnections: function (accountId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return seoConnections.query({ accountId: accountId }, success, error);
		},
		getSeoConnection: function (accountId, id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return seoConnections.get({ accountId: accountId, id: id }, success, error);
		}
	};
});
