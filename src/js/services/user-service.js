launch.module.factory('UserService', function ($resource, $http, $upload, AccountService, ModelMapperService) {
	var resource = $resource('/api/user/:id', { id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.user.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.user.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.user.toDto, transformResponse: ModelMapperService.user.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.user.toDto, transformResponse: ModelMapperService.user.parseResponse },
		delete: { method: 'DELETE' }
	});

	return {
		query: function(callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.query(null, success, error);
		},
		get: function(id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.get({ id: id }, success, error);
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