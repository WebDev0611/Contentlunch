launch.module.factory('AuthService', function($window, $location, $resource, $sanitize, SessionService) {
	var self = this;

	// WE CANNOT PASS IN A ModelMapperService BECAUSE IT WOULD CAUSE A CIRCULAR DEPENDENCY.
	// INSTEAD, CREATE OUR OWN INSTANCE OF THE ModelMapper CLASS.
	self.modelMapper = new launch.ModelMapper($location, this);

	self.cacheSession = function(user) {
		SessionService.set(SessionService.AUTHENTICATED_KEY, true);
		SessionService.set(SessionService.USER_KEY, user);
		SessionService.set(SessionService.ACCOUNT_KEY, user.account);
	};

	self.uncacheSession = function() {
		SessionService.unset(SessionService.AUTHENTICATED_KEY);
		SessionService.unset(SessionService.USER_KEY);
		SessionService.unset(SessionService.ACCOUNT_KEY);
	};

	self.authenticate = $resource('/api/auth', null, {
		login: { method: 'POST', transformResponse: self.modelMapper.auth.parseResponse },
		fetchCurrentUser: { method: 'GET', transformResponse: self.modelMapper.auth.parseResponse }
	});

	self.confirm = $resource('/api/auth/confirm', null, {
		confirm: { method: 'POST', transformResponse: self.modelMapper.user.parseResponse }
	});

	self.impersonate = $resource('/api/auth/impersonate', null, {
		save: { method: 'POST', transformResponse: self.modelMapper.user.parseResponse }
	});

	return {
		login: function (username, password, remember, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return self.authenticate.login({
					email: $sanitize(username),
					password: $sanitize(password),
					remember: remember
				},
				function (r) {
					var user = self.modelMapper.auth.fromDto(r);

					self.cacheSession(user);

					if ($.isFunction(success)) {
						success(user);
					}
				},
				error);
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

			return self.modelMapper.auth.fromCache(JSON.parse(SessionService.get(SessionService.USER_KEY)));
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

			return self.authenticate.fetchCurrentUser(null, function (r) {
				if ($.isFunction(success)) {
					success(r);
				}

				if (!r.id) {
					return;
				}
			}, error);
		},
		forgotPassword: function(email, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			var method = $resource('/api/auth/forgot_password', null, {
				reset: { method: 'POST' }
			});

			return method.reset({ email: email }, success, error);
		},
		confirm: function(code, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return self.confirm.confirm(null, { code: code }, success, error);
		},
		impersonate: function (accountId) {
			self.impersonate.save({ account_id: accountId },
				function (r) {
					self.uncacheSession();
					var user = self.modelMapper.user.fromDto(r);
					self.cacheSession(user);
					$window.location.href = '/';
				});
		},
		impersonateReset: function () {
			self.impersonate.save({ reset: 'true' }, function (r) {
				self.uncacheSession();
				var user = self.modelMapper.user.fromDto(r);
				self.cacheSession(user);
				$window.location.href = '/';
			});
		}
	};
});
