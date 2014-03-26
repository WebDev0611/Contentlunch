launch.module.factory('RoleService', function ($resource, ModelMapperService) {
	var resource = $resource('/api/role/:id', { id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.role.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.role.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.role.formatRequest, transformResponse: ModelMapperService.role.parseResponse },
		add: { method: 'POST', transformRequest: ModelMapperService.role.formatRequest, transformResponse: ModelMapperService.role.parseResponse }
	});

	return {
		query: function(params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.query(params, success, error);
		},
		get: function(id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.get({ id: id }, success, error);
		},
		update: function(role, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.update({ id: role.id }, role, success, error);
		},
		add: function(role, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.add(null, role, success, error);
		},
		getNewRole: function() {
			return new launch.Role();
		}
	};
});