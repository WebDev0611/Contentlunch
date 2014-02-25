
launch.module.factory('AuthService', function ($resource, $sanitize, SessionService) {
	var cacheSession = function () {
		SessionService.set('authenticated', true);
	};

	var uncacheSession = function () {
		SessionService.unset('authenticated');
	};

	var loginError = function (response) {
		
	};

	return {
		login: function (username, password, remember, callbacks) {
			var login = $resource('/api/auth/').save({
				'email': $sanitize(username),
				'password': $sanitize(password),
				'remember': remember
			}, function (resource) {
				cacheSession();

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
			try {
				var logout = $resource('/api/user/logout').get(function(r) {

				});

				uncacheSession();

				return logout;
			} catch (e) {
			}
		},
		isLoggedIn: function () {
			return Boolean(SessionService.get('authenticated'));
		}
	};
});
