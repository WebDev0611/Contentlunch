launch.module.factory('ConnectionService', function ($resource, ModelMapperService) {
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
		providers: $resource('/api/connections'),
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

			// TODO: GET THIS FROM THE API!!
			//return promoteConnections.query({ accountId: accountId }, success, error);

			var hootsuite = new launch.PromoteConnection();
			hootsuite.id = 1;
			hootsuite.accountId = accountId;
			hootsuite.name = 'Hootsuite';
			hootsuite.active = true;
			hootsuite.connectionType = 'promote';
			hootsuite.connectionCategory = 'social';
			hootsuite.connectionSettings = {};
			hootsuite.provider = 'hootsuite';
			hootsuite.url = null;
			hootsuite.created = new Date();
			hootsuite.updated = new Date();

			var hubspot = new launch.PromoteConnection();
			hubspot.id = 1;
			hubspot.accountId = accountId;
			hubspot.name = 'HubSpot';
			hubspot.active = true;
			hubspot.connectionType = 'promote';
			hubspot.connectionCategory = 'automation';
			hubspot.connectionSettings = {};
			hubspot.provider = 'hubspot';
			hubspot.url = null;
			hubspot.created = new Date();
			hubspot.updated = new Date();

			var acton = new launch.PromoteConnection();
			acton.id = 1;
			acton.accountId = accountId;
			acton.name = 'Act-On';
			acton.active = true;
			acton.connectionType = 'promote';
			acton.connectionCategory = 'automation';
			acton.connectionSettings = {};
			acton.provider = 'act-on';
			acton.url = null;
			acton.created = new Date();
			acton.updated = new Date();

			var outbrain = new launch.PromoteConnection();
			outbrain.id = 1;
			outbrain.accountId = accountId;
			outbrain.name = 'Outbrain';
			outbrain.active = true;
			outbrain.connectionType = 'promote';
			outbrain.connectionCategory = 'amplification';
			outbrain.connectionSettings = {};
			outbrain.provider = 'outbrain';
			outbrain.url = null;
			outbrain.created = new Date();
			outbrain.updated = new Date();

			var papershare = new launch.PromoteConnection();
			papershare.id = 1;
			papershare.accountId = accountId;
			papershare.name = 'PaperShare';
			papershare.active = true;
			papershare.connectionType = 'promote';
			papershare.connectionCategory = 'intelligence';
			papershare.connectionSettings = {};
			papershare.provider = 'papershare';
			papershare.url = null;
			papershare.created = new Date();
			papershare.updated = new Date();

			return [hootsuite, hubspot, acton, outbrain, papershare];
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
