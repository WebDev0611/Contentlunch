launch.module.factory('AuthService', function($resource, $sanitize, SessionService, UserService) {
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
				function (r) {
					var user = UserService.mapUserFromDto(r);

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
			if (!this.isLoggedIn()) {
				return { };
			}

			return UserService.setUserFromCache(JSON.parse(SessionService.get('user')));
		},
		getCurrentUser: function(callback) {
			return $resource('/api/auth').get(callback);
		}
	};
});