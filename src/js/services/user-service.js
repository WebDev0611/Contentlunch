launch.module.factory('UserService', function ($resource, $http, $upload, AccountService, ModelMapperService) {
	var users = $resource('/api/user/:id', { id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.user.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.user.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.user.toDto, transformResponse: ModelMapperService.user.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.user.toDto, transformResponse: ModelMapperService.user.parseResponse },
		delete: { method: 'DELETE' }
	});

	var accountUsers = $resource('/api/account/:id/users', { id: '@id' }, {
		get: { method: 'GET', isArray: true, transformResponse: ModelMapperService.user.parseResponse }
	});

	return {
		query: function(callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return users.query(null, success, error);
		},
		getForAccount: function(id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return accountUsers.get({ id: id }, success, error);
		},
		getByRole: function(roles, callback) {
			if (!roles) {
				return this.query(callback);
			}

			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;
			var queryString = '';

			if ($.isArray(roles)) {
				angular.forEach(roles, function(r, i) {
					if (queryString.length === 0) {
						queryString += '?';
					} else {
						queryString += '&';
					}

					queryString += 'roles[]=' + r.roleId;
				});
			} else {
				queryString += '?roles[]=' + roles.roleId;
			}

			var usersCall = $resource('/api/user' + queryString, null, {
				get: { method: 'GET', isArray: true, transformResponse: ModelMapperService.user.parseResponse }
			});

			return usersCall.get(null, success, error);
		},
		get: function(id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return users.get({ id: id }, success, error);
		},
		update: function(user, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return users.update({ id: user.id }, user, success, error);
		},
		add: function(user, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return users.insert(null, user, success, error);
		},
		delete: function(user, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return users.delete({ id: user.id }, user, success, error);
		},
		forgotPassword: function (user, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;


		},
		resetPassword: function (token, password, confirm, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;


		},
		getNewUser: function () {
			return new launch.User();
		},
		savePhoto: function (user, file, callback) {
			$upload.upload({
				url: '/api/user/' + user.id + '/image',
				method: 'POST',
				data: null,
				file: file
			}).progress(function (evt) {
				if (!!callback && $.isFunction(callback.progress)) {
					callback.progress(evt);
				}
			}).success(function (data, status, headers, config) {
				if ((!!callback && $.isFunction(callback.success))) {
					callback.success(ModelMapperService.user.fromDto(data));
				}
			}).error(function (data, status, headers, config) {
				if (!!callback && $.isFunction(callback.error)) {
					callback.error({ data: data, status: status, headers: headers, config: config });
				}
			});
		},
		validatePhotoFile: function (file) {
			if (!$.inArray(file.type, launch.config.USER_PHOTO_FILE_TYPES)) {
				return 'The file you selected is not supported. You may only upload JPG, PNG, GIF, or BMP images.';
			} else if (file.size > 5000000) {
				return 'The file you selected is too big. You may only upload images that are 5MB or less.';
			}

			return null;
		}
	};
});