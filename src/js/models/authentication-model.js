launch.Authentication = function () {
	var self = this;

	self.id = null;
	self.displayName = null;
	self.email = null;
	self.phoneNumber = null;
	self.confirmed = false;
	self.active = true;
	self.image = null;
	self.created = null;
	self.updated = null;
	self.modules = [];

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
					return $.inArray(priv, p.name);
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

	return self;
};