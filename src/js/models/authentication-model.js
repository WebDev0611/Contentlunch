launch.Authentication = function () {
	var self = this;

	self.id = null;
	self.displayName = null;
	self.email = null;
	self.password = null;
	self.phoneNumber = null;
	self.confirmed = false;
	self.active = true;
	self.image = null;
	self.created = null;
	self.updated = null;
	self.modules = [];
	self.preferences = null;

	self.hasImage = function () { return !launch.utils.isBlank(self.image); };
	self.imageUrl = function () { return self.hasImage() ? 'url(\'' + self.image + '\')' : null; };

	self.hasModuleAccess = function(module) {
		return (($.grep(self.modules, function(m) { return m.name === module; })).length > 0);
	};

	self.hasPrivilege = function(priv) {
		for (var i = 0; i < self.modules.length; i++) {
			var length = 0;

			length = ($.grep(self.modules[i].privileges, function (p) {
				if ($.isArray(priv)) {
					return ($.inArray(p.name, priv) >= 0);
				} else {
					return p.name === priv;
				}
			})).length;

			if (length > 0) {
				return true;
			}
		}

		return false;
	};

	self.validateProperty = function(property) {
		if (launch.utils.isBlank(property)) {
			return true;
		}

		switch (property.toLowerCase()) {
			case 'email':
				if (launch.utils.isBlank(self.email)) {
					return 'Please enter your email address.';
				} else if (!launch.utils.isValidEmail(self.email)) {
					return 'Please enter a valid email address.';
				}

				return null;
			case 'password':
				return launch.utils.isBlank(self.password) ? 'Please enter your password.' : null;
			default:
				return null;
		}
	};

	return self;
};