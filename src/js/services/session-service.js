launch.module.factory('SessionService', function() {
	return {
		USER_KEY: 'USER_KEY',
		AUTHENTICATED_KEY: 'AUTHENTICATED_KEY',
		get: function(key) {
			return sessionStorage.getItem(key);
		},
		set: function(key, val) {
			return sessionStorage.setItem(key, (typeof val === 'object') ? JSON.stringify(val) : val);
		},
		unset: function(key) {
			return sessionStorage.removeItem(key);
		}
	};
});