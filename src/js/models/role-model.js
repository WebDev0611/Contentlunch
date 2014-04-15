launch.Role = function(id, name) {
	var self = this;

	self.id = parseInt(id);
	self.name = name;
	self.displayName = null;
	self.isGlobalAdmin = false;
	self.isBuiltIn = false;
	self.isDeletable = false;
	self.accountId = 0;
	self.active = true;
	self.created = null;
	self.updated = null;

	self.modules = [];

	self.matchSearchTerm = function (term) {
		return launch.utils.startsWith(self.name, term);
	};

	self.validateProperty = function (property) {
		switch (property.toLowerCase()) {
			case 'displayname':
				return launch.utils.isBlank(this.displayName) ? 'User Role Name is required.' : null;
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

launch.Privilege = function() {
	var self = this;

	self.name = null;
	self.displayName = null;
	self.module = null;
	self.accessType = null;

	self.view = false;
	self.edit = false;
	self.execute = false;

	return self;
};