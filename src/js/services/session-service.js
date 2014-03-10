launch.module.factory('SessionService', function() {
	return {
		get: function(key) {
			return sessionStorage.getItem(key);
		},
		set: function(key, val) {
			// NOTE: IF THE VALUE OF val IS AN OBJECT, IT WILL BE STORED IN THE SESSION
			//			AS A STRING. IF THIS OBJECT IS JSON REPRESENTING A CLASS THAT
			//			HAS METHODS, THOSE METHODS WILL NOT BE SAVED IN THE JSON STRING.
			return sessionStorage.setItem(key, val);
		},
		unset: function(key) {
			return sessionStorage.removeItem(key);
		}
	};
});