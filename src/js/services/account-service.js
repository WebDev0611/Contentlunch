launch.module.factory('AccountService', function ($resource, $http, ModelMapperService) {
	var accounts = $resource('/api/account/:id', { id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.account.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.account.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.account.formatRequest, transformResponse: ModelMapperService.account.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.account.formatRequest, transformResponse: ModelMapperService.account.parseResponse },
		delete: { method: 'DELETE' },
		getSubscription: { method: 'GET' }
	});

	var subscriptions = $resource('/api/account/:id/subscription', { id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.subscription.parseResponse },
		save: { method: 'POST', transformRequest: ModelMapperService.subscription.formatRequest }
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

			var account = accounts.get({ id: id }, success, error);

			account.$promise.then(function (acct) {
				if (!acct.subscription) {
					acct.subscription = subscriptions.get({ id: acct.id }, null, error);
				}
			});

			return account;
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
		addUser: function (accountId, userId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;
			var resource = $resource('/api/account/:id/add_user', { id: '@id' }, {
				insert: {
					method: 'POST',
					transformRequest: function (uid) {
						return JSON.stringify({ user_id: uid });
					}
				}
			});

			return resource.insert({ id: accountId }, userId, success, error);
		},
		addSubscription: function(accountId, subscription, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			subscriptions.save({ id: accountId }, subscription, success, error);
		},
		getNewAccount: function () {
			var account = new launch.Account();

			account.creditCard = new launch.CreditCard();
			account.bankAccount = new launch.BankAccount();
			account.subscription = new launch.Subscription(1);

			return account;
		}
	};
});