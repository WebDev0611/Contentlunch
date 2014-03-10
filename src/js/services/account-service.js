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
			account.address1 = dto.address;
			account.address2 = dto.address_2;
			account.city = dto.city;
			account.state = { value: dto.state, name: null };
			//account.postalCode = null;
			//account.country = null;
			//account.email = null;
			account.phoneNumber = dto.phone;
			account.autoRenew = (parseInt(dto.subscription) === 1);
			account.created = dto.created_at;
			account.updated = dto.updated_at;

			account.creditCard = new launch.CreditCard();
			//account.creditCard.cardNumber = null;
			//account.creditCard.nameOnCard = null;
			//account.creditCard.cvc = null;
			//account.creditCard.expirationDateMonth = null;
			//account.creditCard.expirationDateYear = null;
			//account.creditCard.address1 = null;
			//account.creditCard.address2 = null;
			//account.creditCard.city = null;
			//account.creditCard.country = null;
			//account.creditCard.state = null;
			//account.creditCard.postalCode = null;

			return account;
		},
		toDto: function(account) {
			var dto = {
				id: account.id,
				title: account.title,
				name: account.title,
				active: (account.active === 'active') ? 1 : 0,
				address: account.address1,
				address_2: account.address2,
				city: account.city,
				state: (!!account.state) ? account.state.value : null,
				phone: account.phoneNumber,
				subscription: account.autoRenew ? 1 : 0,
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