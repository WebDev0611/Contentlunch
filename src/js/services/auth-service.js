launch.module.factory('AuthService', function($resource, $sanitize, SessionService) {
	var cacheSession = function(user) {
		SessionService.set('authenticated', true);
		SessionService.set('user', JSON.stringify(user));
	};

	var uncacheSession = function() {
		SessionService.unset('authenticated');
		SessionService.unset('user');
	};

	return {
		login: function(username, password, remember, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return $resource('/api/auth/').save({
					email: $sanitize(username),
					password: $sanitize(password),
					remember: remember
				},
				function(r) {
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

					if ($.isFunction(success)) {
						success(r);
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