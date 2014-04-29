launch.module.factory('ConnectionService', function ($resource, ModelMapperService) {
	var contentConnections = $resource('/api/account/:accountId/connections/:id?type=content', { accountId: '@accountId', id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.contentConnection.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.contentConnection.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.contentConnection.formatRequest, transformResponse: ModelMapperService.contentConnection.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.contentConnection.formatRequest, transformResponse: ModelMapperService.contentConnection.parseResponse },
		delete: { method: 'DELETE' }
	});

	var seoConnections = $resource('/api/account/:accountId/connections/:id?type=seo', { accountId: '@accountId', id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.seoConnection.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.seoConnection.parseResponse }
	});

	return {
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