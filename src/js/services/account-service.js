launch.module.factory('AccountService', function ($resource, $http) {
	var map = {
		parseResponse: function(r, getHeaders) {
			var dto = JSON.parse(r);

			if ($.isArray(dto)) {
				var accounts = [];

				angular.forEach(dto, function (account, index) {
					accounts.push(map.fromDto(account));
				});

				accounts.sort(function (a, b) {
					if (a.title === b.title) {
						if (a.id === b.id) {
							return 0;
						} else if (a.id < b.id) {
							return -1;
						} else {
							return 1;
						}
					} else {
						if (a.title < b.title) {
							return -1;
						} else {
							return 1;
						}
					}
				});

				return accounts;
			}

			if ($.isPlainObject(dto)) {
				return map.fromDto(dto);
			}

			return null;
		},
		fromDto: function(dto) {
			var account = new launch.Account();
			
			account.id = parseInt(dto.id);
			account.title = dto.title;
			account.active = (parseInt(dto.active) === 1) ? 'active' : 'inactive';
			//self.address1 = null;
			//self.address2 = null;
			//self.city = null;
			//self.state = null;
			//self.postalCode = null;
			//self.country = null;
			//self.email = null;
			//self.phoneNumber = null;
			//self.autoRenew = false;
			account.created = dto.created_at;
			account.updated = dto.updated_at;

			self.creditCard = new launch.CreditCard();
			//self.creditCard.cardNumber = null;
			//self.creditCard.nameOnCard = null;
			//self.creditCard.cvc = null;
			//self.creditCard.expirationDateMonth = null;
			//self.creditCard.expirationDateYear = null;
			//self.creditCard.address1 = null;
			//self.creditCard.address2 = null;
			//self.creditCard.city = null;
			//self.creditCard.country = null;
			//self.creditCard.state = null;
			//self.creditCard.postalCode = null;

			return account;
		},
		toDto: function(account) {
			var dto = {
				id: account.id,
				title: account.title,
				active: (account.active === 'active') ? 1 : 0,
				created_at: account.created,
				updated_at: account.updated
			};

			return JSON.stringify(dto);
		}
	};

	var resource = $resource('/api/account/:id', { id: '@id' }, {
		get: { method: 'GET', transformResponse: map.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: map.parseResponse },
		update: { method: 'PUT', transformRequest: map.toDto, transformResponse: map.parseResponse },
		insert: { method: 'POST', transformRequest: map.toDto, transformResponse: map.parseResponse },
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
		},
		mapAccountFromDto: function(dto) {
			return map.fromDto(dto);
		}
	};
});