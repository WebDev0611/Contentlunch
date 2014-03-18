launch.module.factory('AccountService', function ($resource, $http, ModelMapperService) {
	var accounts = $resource('/api/account/:id', { id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.account.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.account.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.account.toDto, transformResponse: ModelMapperService.account.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.account.toDto, transformResponse: ModelMapperService.account.parseResponse },
		delete: { method: 'DELETE' }
	});

	return {
		query: function (callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return accounts.query(null, success, error);
		},
		get: function (id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return accounts.get({ id: id }, success, error);
		},
		update: function (account, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return accounts.update({ id: account.id }, account, success, error);
		},
		add: function (account, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return accounts.insert(null, account, success, error);
		},
		delete: function (account, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return accounts.delete({ id: account.id }, account, success, error);
		},
		getNewAccount: function() {
			return new launch.Account();
		}
	};
});