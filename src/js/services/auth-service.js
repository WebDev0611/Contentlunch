
launch.module.factory('AuthService', function($resource, $sanitize, SessionService) {
	var cacheSession = function(user) {
		SessionService.set('authenticated', true);
		SessionService.set('user', JSON.stringify(user));
	};

	var uncacheSession = function() {
		SessionService.unset('authenticated');
		SessionService.unset('user');
	};

	var loginError = function(response) {

	};

	return {
		login: function(username, password, remember, callbacks) {
			var login = $resource('/api/auth/').save({
				email: $sanitize(username),
				password: $sanitize(password),
				remember: remember
			}, function(r) {
				var user = {
					id: parseInt(r.id),
					confirmed: r.confirmed,
					created_at: r.created_at,
					email: r.email,
					first_name: r.first_name,
					last_name: r.last_name,
					updated_at: r.updated_at,
					username: r.username
				};

				cacheSession(user);

				if (!!callbacks && $.isFunction(callbacks.success)) {
					callbacks.success(r);
				}
			}, function(r) {
				loginError(r);

				if (!!callbacks && $.isFunction(callbacks.error)) {
					callbacks.error(r);
				}
			});

			return login;
		},
		logout: function() {
			var logout = $resource('/api/auth/logout').get(function(r) {
				uncacheSession();
			});

			return logout;
		},
		isLoggedIn: function() {
			return Boolean(SessionService.get('authenticated'));
		},
		userInfo: function() {
			return this.isLoggedIn() ? JSON.parse(SessionService.get('user')) : { };
		},
		getCurrentUser: function(callback) {
			return $resource('/api/auth').get(callback);
		}
	};
});
