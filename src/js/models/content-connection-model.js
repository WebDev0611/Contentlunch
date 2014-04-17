launch.ContentConnection = function() {
	var self = this;

	self.id = null;
	self.name = null;
	self.active = true;
	self.connectionType = null;
	self.url = null;

	self.matchSearchTerm = function (term) {
		return launch.utils.startsWith(self.name, term) || launch.utils.startsWith(self.connectionType, term);
	};

	self.validateProperty = function (property) {
		switch (property.toLowerCase()) {
			case 'name':
				return launch.utils.isBlank(this.name) ? 'Connection Name is required.' : null;
			case 'active':
				if (typeof this.active !== 'boolean') { this.active = (this.active === 1 || this.active === '1' || this.active.toLowerCase() === 'true'); }

				return (this.active === true || this.active === false) ? null : 'Active Status is required.';
			default:
				return null;
		}
	};

	return self;
};