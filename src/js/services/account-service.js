launch.module.factory('AccountService', function ($resource, $http, ModelMapperService) {
	var resource = $resource('/api/account/:id', { id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.account.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.account.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.account.toDto, transformResponse: ModelMapperService.account.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.account.toDto, transformResponse: ModelMapperService.account.parseResponse },
		delete: { method: 'DELETE' }
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
		update: function (account, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.update({ id: account.id }, account, success, error);
		},
		add: function (account, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.insert(null, account, success, error);
		},
		delete: function (account, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.delete({ id: account.id }, account, success, error);
		},
		getNewAccount: function() {
			return new launch.Account();
		}
	};
});