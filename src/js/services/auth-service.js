
launch.module.factory('AuthService', function ($resource, $sanitize, SessionService) {
	var cacheSession = function (user) {
		SessionService.set('authenticated', true);
		SessionService.set('user', JSON.stringify(user));
	};

	var uncacheSession = function () {
		SessionService.unset('authenticated');
		SessionService.unset('user');
	};

	var loginError = function (response) {
		
	};

	return {
		login: function (username, password, remember, callbacks) {
			var login = $resource('/api/auth/').save({
				email: $sanitize(username),
				password: $sanitize(password),
				remember: remember
			}, function (resource) {
				cacheSession(resource.user);

				if (!!callbacks && $.isFunction(callbacks.success)) {
					callbacks.success(resource);
				}
			}, function (resource) {
				loginError(resource);

				if (!!callbacks && $.isFunction(callbacks.error)) {
					callbacks.error(resource);
				}
			});

			return login;
		},
		logout: function () {
			var logout = $resource('/api/auth/logout').get(function (r) {
				uncacheSession();
			});

			return logout;
		},
		isLoggedIn: function () {
			return Boolean(SessionService.get('authenticated'));
		},
		userInfo: function() {
			return this.isLoggedIn() ? JSON.parse(SessionService.get('user')) : { };
		}
	};
});
