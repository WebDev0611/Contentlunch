launch.Role = function(id, name) {
	var self = this;

	self.id = parseInt(id);
	self.name = name;
	self.active = true;
	self.created = null;
	self.updated = null;

	self.isGlobalAdmin = function() {
		return (!launch.utils.isBlank(self.name) && self.name.toUpperCase() === 'ADMIN');
	};

	self.matchSearchTerm = function (term) {
		if (launch.utils.startsWith(self.name, term)) {
			return true;
		}

		return false;
	};

	self.validateProperty = function (property) {
		switch (property.toLowerCase()) {
			case 'name':
				return launch.utils.isBlank(this.name) ? 'User Role Name is required.' : null;
			case 'active':
				if (typeof this.active !== 'boolean') {
					this.active = (this.active === 1 || this.active === '1' || this.active.toLowerCase() === 'true');
				}
				return (this.active === true || this.active === false) ? null : 'Active Status is required.';
			default:
				return null;
		}
	};

	return self;
};