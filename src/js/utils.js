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
	},

	startsWith: function (s1, s2) {
		if (!this.isBlank(s1) && !this.isBlank(s2)) {
			return (s1.toLowerCase().match('^' + s2.toLowerCase()) !== null);
		}

		return false;
	},

	endsWith: function (s1, s2) {
		if (!this.isBlank(s1) && !this.isBlank(s2)) {
			return (s1.toLowerCase().match(s2.toLowerCase() + '$') !== null);
		}

		return false;
	},

	isValidPattern: function (s, pattern) {
		if (this.isBlank(s)) {
			return false;
		}

		if (!(new RegExp(pattern).test(s))) {
			return false;
		}

		return true;
	},

	isValidEmail: function (s) {
		return this.isValidPattern(s, launch.config.EMAIL_ADDRESS_REGEX);
	}
};