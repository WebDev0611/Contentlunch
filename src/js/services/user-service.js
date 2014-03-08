
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
					var firstA = launch.utils.isBlank(a.firstName) ? '' : a.firstName.toUpperCase();
					var firstB = launch.utils.isBlank(b.firstName) ? '' : b.firstName.toUpperCase();
					var lastA = launch.utils.isBlank(a.lastName) ? '' : a.lastName.toUpperCase();
					var lastB = launch.utils.isBlank(b.lastName) ? '' : b.lastName.toUpperCase();

					if (lastA === lastB) {
						if (firstA === firstB) {
							return 0;
						} else if (firstA < firstB) {
							return -1;
						} else {
							return 1;
						}
					} else {
						if (lastA < lastB) {
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

			user.id = parseInt(dto.id);
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
			user.country = dto.country;
			user.state = { value: dto.state, name: null };
			user.phoneNumber = dto.phone;
			user.title = dto.title;
			user.username = dto.username;
			user.active = (parseInt(dto.status) === 1) ? 'active' : 'inactive';
			user.role = (roles.length > 0) ? roles[0] : null;

			//user.image = '/assets/images/testing-user-image.png';

			return user;
		},
		toDto: function(user) {
			var dto = {
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
				country: user.country,
				phone: user.phoneNumber,
				title: user.title,
				status: (user.active === 'active') ? 1 : 0,
				roles: [{ id: user.role.roleId, name: user.role.roleName }]
			};

			if (!launch.utils.isBlank(user.password) && !launch.utils.isBlank(user.passwordConfirmation)) {
				dto.password = user.password;
				dto.password_confirmation = user.passwordConfirmation;
			}

			return JSON.stringify(dto);
		}
	};

	var resource = $resource('/api/user/:id', { id: '@id' }, {
		get: { method: 'GET', transformResponse: map.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: map.parseResponse },
		update: { method: 'PUT', transformRequest: map.toDto, transformResponse: map.parseResponse },
		insert: { method: 'POST', transformRequest: map.toDto, transformResponse: map.parseResponse },
		delete: { method: 'DELETE' }
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
		update: function(user, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.update({ id: user.id }, user, success, error);
		},
		add: function(user, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.insert(null, user, success, error);
		},
		delete: function(user, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.delete({ id: user.id }, user, success, error);
		},
		savePhoto: function(user, file, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			
		},
		getNewUser: function() {
			return new launch.User();
		}
	};
});
