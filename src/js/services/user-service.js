
launch.module.factory('UserService', function($resource) {
	var map = {
		parseResponse: function(r, getHeaders) {
			var dto = JSON.parse(r);

			if ($.isArray(dto)) {
				var users = [];

				angular.forEach(dto, function(user, index) {
					users.push(map.fromDto(user));
				});

				users.sort(function(a, b) {
					if (launch.utils.isBlank(a.lastName) && launch.utils.isBlank(b.lastName) &&
						launch.utils.isBlank(a.firstName) && launch.utils.isBlank(b.firstName)) {
						return 0;
					}

					if (a.lastName === b.lastName) {
						if (launch.utils.isBlank(a.firstName) && launch.utils.isBlank(b.firstName)) {
							return 0;
						} else if (a.firstName === b.firstName) {
							return 0;
						} else if (a.firstName < b.firstName) {
							return -1;
						} else {
							return 1;
						}
					} else {
						if (a.lastName < b.lastName) {
							return -1;
						} else {
							return 1;
						}
					}
				});

				return users;
			}

			if ($.isPlainObject(dto)) {
				return map.fromDto(dto);
			}

			return null;
		},
		fromDto: function(dto) {
			var user = new launch.User();
			var roles = [];

			angular.forEach(dto.roles, function(r, i) {
				roles.push(new launch.Role(r.id, r.name));
			});

			user.id = dto.id;
			user.userName = dto.userName;
			user.firstName = dto.first_name;
			user.lastName = dto.last_name;
			user.email = dto.email;
			user.created = dto.created_at;
			user.updated = dto.updated_at;
			user.confirmed = dto.confirmed;
			user.address1 = dto.address;
			user.address2 = dto.address_2;
			user.city = dto.city;
			user.country = 'USA';
			user.state = { value: dto.state, name: null };
			user.phoneNumber = dto.phone;
			user.title = dto.title;
			user.username = dto.username;
			user.active = (parseInt(dto.status) === 1) ? 'active' : 'inactive';
			user.role = (roles.length > 0) ? roles[0] : null;

			//user.image = '/assets/images/testing-user-image.png';

			return user;
		},
		toDto: function (user) {
			return JSON.stringify({
				id: user.id,
				userName: user.userName,
				first_name: user.firstName,
				last_name: user.lastName,
				email: user.email,
				created_at: user.created,
				updated_at: user.updated,
				confirmed: user.confirmed,
				address: user.address1,
				address_2: user.address2,
				city: user.city,
				state: (!!user.state) ? user.state.value : null,
				phone: user.phoneNumber,
				title: user.title,
				status: (user.active === 'active') ? 1 : 0,
				roles: [{ id: user.role.roleId, name: user.role.roleName }]
			});
		}
	};

	var resource = $resource('/api/user/:id', { id: '@id' }, {
		get: { method: 'GET', transformResponse: map.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: map.parseResponse },
		update: { method: 'PUT', transformRequest: map.toDto, transformResponse: map.parseResponse }
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
		update: function (user, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.update({ id: user.id }, user, success, error);
		},
		getNewUser: function() {
			return new launch.User();
		}
	};
});
