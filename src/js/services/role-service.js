launch.module.factory('RoleService', function ($resource, ModelMapperService) {
	var roles = $resource('/api/role/:id', { id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.role.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.role.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.role.formatRequest, transformResponse: ModelMapperService.role.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.role.formatRequest, transformResponse: ModelMapperService.role.parseResponse },
		delete: { method: 'DELETE' }
	});

	var accountRoles = $resource('/api/account/:accountId/roles/:id', { accountId: '@accountId', id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.role.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.role.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.role.formatRequest, transformResponse: ModelMapperService.role.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.role.formatRequest, transformResponse: ModelMapperService.role.parseResponse },
		delete: { method: 'DELETE' }
	});

	return {
		query: function (accountId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return accountRoles.query({ accountId: accountId, id: '' }, success, error);
		},
		get: function(id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return accountRoles.get({ accountId: role.accountId, id: id }, success, error);
		},
		update: function(role, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return accountRoles.update({ accountId: role.accountId, id: role.id }, role, success, error);
		},
		add: function(role, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return accountRoles.insert({ accountId: role.accountId, id: '' }, role, success, error);
		},
		delete: function(role, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return accountRoles.delete({ accountId: role.accountId, id: role.id }, role, success, error);
		},
		getNewRole: function (accountId) {
			var role = new launch.Role();

			role.accountId = accountId;

			return role;
		}
	};
});