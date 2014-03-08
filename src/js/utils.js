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

	startsWith: function(s1, s2) {
		if (!this.isBlank(s1) && !this.isBlank(s2)) {
			return (s1.toLowerCase().match('^' + s2.toLowerCase()) !== null);
		}

		return false;
	},

	endsWith: function(s1, s2) {
		if (!this.isBlank(s1) && !this.isBlank(s2)) {
			return (s1.toLowerCase().match(s2.toLowerCase() + '$') !== null);
		}

		return false;
	},

	isValidPattern: function(s, pattern) {
		if (this.isBlank(s)) {
			return false;
		}

		if (!(new RegExp(pattern).test(s))) {
			return false;
		}

		return true;
	},

	isValidEmail: function(s) {
		return this.isValidPattern(s, launch.config.EMAIL_ADDRESS_REGEX);
	},

	handleAjaxErrorResponse: function(response, notificationService) {
		var err = (!launch.utils.isBlank(response.message)) ? response.message : null;
		var msg = 'Looks like we\'ve encountered an error.';
		var title = 'Whoops!';

		if (launch.utils.isBlank(err) && !!response.data) {
			if (!launch.utils.isBlank(response.data.flash)) {
				msg = response.data.flash;
			} else if (!!response.data.error) {
				err = '';

				if (!launch.utils.isBlank(response.data.error.message)) { err += '\n\nMessage: ' + response.data.error.message; }

				if (launch.config.DEBUG_MODE) {
					if (!launch.utils.isBlank(response.data.error.type)) { err += '\n\nType: ' + response.data.error.type; }
					if (!launch.utils.isBlank(response.data.error.file)) { err += '\n\nFile: ' + response.data.error.file; }
					if (!launch.utils.isBlank(response.data.error.line)) { err += '\n\nLine: ' + response.data.error.line; }
				}
			}
		}

		if (!launch.utils.isBlank(err)) {
			msg += ' Here is more information:' + err;
		}

		notificationService.error(title, msg);
	}
};