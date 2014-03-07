
launch.module.factory('RoleService', function ($resource) {
	var map = {
		parseResponse: function(r, getHeaders) {
			var dto = JSON.parse(r);

			if ($.isArray(dto)) {
				var roles = [];

				angular.forEach(dto, function (role, index) {
					roles.push(map.fromDto(role));
				});

				roles.sort(function (a, b) {
					var roleA = launch.utils.isBlank(a.roleName) ? '' : a.roleName.toUpperCase();
					var roleB = launch.utils.isBlank(b.roleName) ? '' : b.roleName.toUpperCase();

					if (roleA === roleB) {
						if (a.roleId === b.roleId) {
							return 0;
						} else if (a.roleId < b.roleId) {
							return -1;
						} else {
							return 1;
						}
					} else {
						if (roleA < roleB) {
							return -1;
						} else {
							return 1;
						}
					}
				});

				return roles;
			}

			if ($.isPlainObject(dto)) {
				return map.fromDto(dto);
			}

			return null;

		},
		fromDto: function(dto) {
			var role = new launch.Role(dto.id, dto.name);

			role.created = dto.created_at;
			role.updated = dto.updated_at;

			return role;
		},
		toDto: function(role) {
			return {
				id: role.roleId,
				name: roleName,
				created_at: role.created,
				updated_at: role.updated
			};
		}
	};

	var resource = $resource('/api/role/:id', { id: '@id' }, {
		get: { method: 'GET', transformResponse: map.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: map.parseResponse },
		update: { method: 'PUT', transformRequest: map.toDto, transformResponse: map.parseResponse }
	});

	return {
		query: function (params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.query(params, success, error);
		},
		get: function (params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.get(params, success, error);
		},
		update: function (role, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.update({ id: role.id }, role, success, error);
		}
	};
});
