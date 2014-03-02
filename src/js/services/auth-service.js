
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
			}, function(resource) {
				var user = {
					id: resource.id,
					confirmed: resource.confirmed,
					created_at: resource.created_at,
					email: resource.email,
					first_name: resource.first_name,
					last_name: resource.last_name,
					updated_at: resource.updated_at,
					username: resource.username
				};

				cacheSession(user);

				if (!!callbacks && $.isFunction(callbacks.success)) {
					callbacks.success(resource);
				}
			}, function(resource) {
				loginError(resource);

				if (!!callbacks && $.isFunction(callbacks.error)) {
					callbacks.error(resource);
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
