launch.module.factory('SessionService', function() {
	return {
		USER_KEY: 'USER_KEY',
		ACCOUNT_KEY: 'ACCOUNT_KEY',
		AUTHENTICATED_KEY: 'AUTHENTICATED_KEY',
		CONTENT_TYPES_KEY: 'CONTENT_TYPES_KEY',
		ACCOUNT_USERS_KEY: 'ACCOUNT_USERS_KEY',
		get: function(key) {
//			return sessionStorage.getItem(key);
			return localStorage.getItem(key);
		},
		set: function(key, val) {
//			return sessionStorage.setItem(key, (typeof val === 'object') ? JSON.stringify(val) : val);
			return localStorage.setItem(key, (typeof val === 'object') ? JSON.stringify(val) : val);
		},
		unset: function(key) {
//			return sessionStorage.removeItem(key);
			return localStorage.removeItem(key);
		},
		clear: function() {
			this.unset(this.USER_KEY);
			this.unset(this.ACCOUNT_KEY);
			this.unset(this.AUTHENTICATED_KEY);
			this.unset(this.CONTENT_TYPES_KEY);
			this.unset(this.ACCOUNT_USERS_KEY);
		}
	};
});