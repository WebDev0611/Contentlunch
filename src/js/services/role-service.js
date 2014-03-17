launch.module.factory('RoleService', function ($resource, ModelMapperService) {
	var resource = $resource('/api/role/:id', { id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.role.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.role.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.role.toDto, transformResponse: ModelMapperService.role.parseResponse }
	});

	return {
		query: function(params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.query(params, success, error);
		},
		get: function(params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.get(params, success, error);
		},
		update: function(role, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.update({ id: role.id }, role, success, error);
		}
	};
});