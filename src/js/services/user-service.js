launch.module.factory('UserService', function($resource, $upload, AccountService, ModelMapperService, SessionService) {
	var users = $resource('/api/user/:id', { id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.user.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.user.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.user.formatRequest, transformResponse: ModelMapperService.user.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.user.formatRequest, transformResponse: ModelMapperService.user.parseResponse },
		delete: { method: 'DELETE' },
	});

	var preferences = $resource('/api/user/:id/preferences/:key', { id: '@id', key: '@key' }, {
		insert: { method: 'POST' }
	});

	var accountUsers = $resource('/api/account/:id/users', { id: '@id' }, {
		get: { method: 'GET', isArray: true, transformResponse: ModelMapperService.user.parseResponse }
	});

	return {
		query: function(params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return users.query(params, success, error);
		},
		getForAccount: function(accountId, params, callback, forceRefresh) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;
			var accountUserItems = null;

			if (!forceRefresh) {
				accountUserItems = SessionService.get(SessionService.ACCOUNT_USERS_KEY);

				if (!launch.utils.isBlank(accountUserItems)) {
					accountUserItems = JSON.parse(accountUserItems);

					return $.map(accountUserItems, function(u) {
						return ModelMapperService.user.fromCache(u);
					});
				}
			}

			accountUserItems = accountUsers.get(_.merge({ id: accountId }, params), function(r) {
				SessionService.set(SessionService.ACCOUNT_USERS_KEY, JSON.stringify(accountUserItems));

				if (!!success) {
					success(r);
				}
			}, error);

			return accountUserItems;
		},
		getByRole: function(roles, callback) {
			if (!roles) {
				return this.query(callback);
			}

			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;
			var queryString = '';

			if ($.isArray(roles)) {
				$.each(roles, function(i, r) {
					if (queryString.length === 0) {
						queryString += '?';
					} else {
						queryString += '&';
					}

					queryString += 'roles[]=' + r.id;
				});
			} else {
				queryString += '?roles[]=' + roles.id;
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
		forgotPassword: function(user, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

		},
		resetPassword: function(token, password, confirm, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

		},
		getNewUser: function() {
			return new launch.User();
		},
		savePhoto: function(user, file, callback) {
			$upload.upload({
				url: '/api/user/' + user.id + '/image',
				method: 'POST',
				data: null,
				file: file
			}).progress(function(evt) {
				if (!!callback && $.isFunction(callback.progress)) {
					callback.progress(evt);
				}
			}).success(function(data, status, headers, config) {
				if ((!!callback && $.isFunction(callback.success))) {
					callback.success(ModelMapperService.user.fromDto(data));
				}
			}).error(function(data, status, headers, config) {
				if (!!callback && $.isFunction(callback.error)) {
					callback.error({ data: data, status: status, headers: headers, config: config });
				}
			});
		},
		savePreferences: function(userId, key, savePreferences, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return preferences.insert({ id: userId, key: key }, { preferences: savePreferences }, success, error);
		},
		validatePhotoFile: function(file) {
			if ($.inArray(file.type, launch.config.USER_PHOTO_FILE_TYPES) < 0) {
				return 'The file you selected is not supported. You may only upload JPG, PNG, GIF, or BMP images.';
			} else if (file.size > 5000000) {
				return 'The file you selected is too big. You may only upload images that are 5MB or less.';
			}

			return null;
		}
	};
});