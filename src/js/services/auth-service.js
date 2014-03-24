launch.module.factory('AuthService', function ($resource, $sanitize, SessionService) {
	var self = this;

	// WE CANNOT PASS IN A ModelMapperService BECAUSE IT WOULD CAUSE A CIRCULAR DEPENDENCY.
	// INSTEAD, CREATE OUR OWN INSTANCE OF THE ModelMapper CLASS.
	self.modelMapper = new launch.ModelMapper(this);

	self.cacheSession = function (user) {
		SessionService.set(SessionService.AUTHENTICATED_KEY, true);
		SessionService.set(SessionService.USER_KEY, user);
		SessionService.set(SessionService.ACCOUNT_KEY, user.accounts[0]);
	};

	self.uncacheSession = function () {
		SessionService.unset(SessionService.AUTHENTICATED_KEY);
		SessionService.unset(SessionService.USER_KEY);
		SessionService.unset(SessionService.ACCOUNT_KEY);
	};

	self.resource = $resource('/api/auth', null, {
		login: { method: 'POST' },
		fetchCurrentUser: { method: 'GET', transformResponse: self.modelMapper.user.parseResponse }
	});

	return {
		login: function(username, password, remember, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return self.resource.login({
					email: $sanitize(username),
					password: $sanitize(password),
					remember: remember
				},
				function(r) {
					var user = self.modelMapper.user.fromDto(r);

					self.cacheSession(user);

					if ($.isFunction(success)) {
						success(user);
					}
				},
				function(r) {
					if ($.isFunction(error)) {
						error(r);
					}
				});
		},
		logout: function() {
			return $resource('/api/auth/logout').get(function(r) {
				self.uncacheSession();
			});
		},
		isLoggedIn: function() {
			return Boolean(SessionService.get(SessionService.AUTHENTICATED_KEY));
		},
		userInfo: function() {
			if (!this.isLoggedIn()) {
				return { };
			}

			return self.modelMapper.user.fromCache(JSON.parse(SessionService.get(SessionService.USER_KEY)));
		},
		accountInfo: function() {
			if (!this.isLoggedIn()) {
				return { };
			}

			return self.modelMapper.account.fromCache(JSON.parse(SessionService.get(SessionService.ACCOUNT_KEY)));
		},
		fetchCurrentUser: function(callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return self.resource.fetchCurrentUser(null, success, error);
		}
	};
});