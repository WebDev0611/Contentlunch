﻿launch.module.factory('RoleService', function ($resource, ModelMapperService) {
	var roles = $resource('/api/role/:id', { id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.role.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.role.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.role.formatRequest, transformResponse: ModelMapperService.role.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.role.formatRequest, transformResponse: ModelMapperService.role.parseResponse },
		delete: { method: 'DELETE' }
	});

	return {
		query: function(params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return roles.query(params, success, error);
		},
		get: function(id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return roles.get({ id: id }, success, error);
		},
		update: function(role, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return roles.update({ id: role.id }, role, success, error);
		},
		add: function(role, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return roles.insert({ id: '' }, role, success, error);
		},
		getNewRole: function() {
			return new launch.Role();
		}
	};
});