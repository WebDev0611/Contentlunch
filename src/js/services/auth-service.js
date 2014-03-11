launch.module.factory('AuthService', function($resource, $sanitize, SessionService, UserService, AccountService) {
	var cacheSession = function(user) {
		SessionService.set(SessionService.AUTHENTICATED_KEY, true);
		SessionService.set(SessionService.USER_KEY, user);
		SessionService.set(SessionService.ACCOUNT_KEY, user.accounts[0]);
	};

	var uncacheSession = function() {
		SessionService.unset(SessionService.AUTHENTICATED_KEY);
		SessionService.unset(SessionService.USER_KEY);
		SessionService.unset(SessionService.ACCOUNT_KEY);
	};

	var resource = $resource('/api/auth', null, {
		fetchCurrentUser: { method: 'GET', transformResponse: UserService.mapUserFromDto }
	});

	return {
		login: function(username, password, remember, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return $resource('/api/auth/').save({
					email: $sanitize(username),
					password: $sanitize(password),
					remember: remember
				},
				function (r) {
					var user = UserService.mapUserFromDto(r);

					cacheSession(user);

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
				uncacheSession();
			});
		},
		isLoggedIn: function() {
			return Boolean(SessionService.get(SessionService.AUTHENTICATED_KEY));
		},
		userInfo: function() {
			if (!this.isLoggedIn()) {
				return { };
			}

			return UserService.setUserFromCache(JSON.parse(SessionService.get(SessionService.USER_KEY)));
		},
		accountInfo: function() {
			if (!this.isLoggedIn()) {
				return {};
			}

			return AccountService.setAccountFromCache(JSON.parse(SessionService.get(SessionService.ACCOUNT_KEY)));
		},
		fetchCurrentUser: function(callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.fetchCurrentUser(null, success, error);
		}
	};
});