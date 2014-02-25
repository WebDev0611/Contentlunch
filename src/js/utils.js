launch.utils = {
	isBlank: function(str) {
		var i;

		if (!!str) {
			if (typeof str === 'number' || typeof str === 'boolean' || typeof str === 'object' || typeof str === 'function') {
				return false;
			}

			for (i = 0; i < str.length; i++) {
				if (str.charCodeAt(i) >= 33) {
					return false;
				}
			}
		}

		return true;
	}
};