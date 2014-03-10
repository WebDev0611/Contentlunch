launch.module.factory('UserService', function($resource, $http, $upload) {
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

	var resource = $resource('/api/user/:id/:image', { id: '@id', image: '@image' }, {
		get: { method: 'GET', transformResponse: map.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: map.parseResponse },
		update: { method: 'PUT', transformRequest: map.toDto, transformResponse: map.parseResponse },
		insert: { method: 'POST', transformRequest: map.toDto, transformResponse: map.parseResponse },
		//savePhoto: { method: 'POST' },
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
			//var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			//var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			$upload.upload({
				url: '/api/user/' + user.id + '/image/',
				method: 'POST',
				data: null,
				file: file
			}).progress(function (evt) {
				console.log('percent: ' + parseInt(100.0 * evt.loaded / evt.total));

				if (!!callback && $.isFunction(callback.progress)) {
					callback.progress(evt);
				}
			}).success(function (data, status, headers, config) {
				console.log(data);

				if ((!!callback && $.isFunction(callback.success))) {
					callback.success(data);
				}
			}).error(function (data, status, headers, config) {
				console.log(data);

				if (!!callback && $.isFunction(callback.error)) {
					callback.error({ data: data, status: status, headers: headers, config: config });
				}
			});

			//launch.utils.convertFileToByteArray(file, function(f) {
			//	//	var payload = {
			//	//		file: f,
			//	//		contentType: file.type,
			//	//		contentEncoding: 'base64'
			//	//	};

			//	//	return resource.savePhoto({ id: user.id, image: 'image' }, payload.file, success, error);

			//	var formData = new FormData();

			//	formData.append('file', f);

			//	$http.post('/api/user/' + user.id + '/image/', formData, {
			//			headers: { 'Content-Type': undefined, 'Content-Encoding': 'base64' },
			//			transformRequest: angular.identity
			//		})
			//		.success(success)
			//		.error(error);
			//});
		},
		getNewUser: function() {
			return new launch.User();
		},
		mapUserFromDto: function(dto) {
			return map.fromDto(dto);
		},
		setUserFromCache: function(cachedUser) {
			var user = new launch.User();

			user.id = cachedUser.id;
			user.userName = cachedUser.userName;
			user.firstName = cachedUser.firstName;
			user.lastName = cachedUser.lastName;
			user.email = cachedUser.email;
			user.created = cachedUser.created;
			user.updated = cachedUser.updated;
			user.confirmed = cachedUser.confirmed;
			user.address1 = cachedUser.address1;
			user.address2 = cachedUser.address2;
			user.city = cachedUser.city;
			user.country = cachedUser.country;
			user.state = cachedUser.state;
			user.phoneNumber = cachedUser.phoneNumber;
			user.title = cachedUser.title;
			user.active = cachedUser.active;
			user.image = cachedUser.image;
			user.role = cachedUser.role;
			user.roles = cachedUser.roles;

			return user;
		},
		validatePhotoFile: function(file) {
			if (!$.inArray(file.type, launch.config.USER_PHOTO_FILE_TYPES)) {
				return 'The file you selected is not supported. You may only upload JPG, PNG, GIF, or BMP images.';
			} else if (file.size > 5000000) {
				return 'The file you selected is too big. You may only upload images that are 5MB or less.';
			}

			return null;
		}
	};
});