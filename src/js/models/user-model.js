launch.User = function() {
	var self = this;

	self.id = null;
	self.userName = null;
	self.firstName = null;
	self.lastName = null;
	self.email = null;
	self.created = null;
	self.updated = null;
	self.confirmed = false;
	self.address1 = null;
	self.address2 = null;
	self.city = null;
	self.country = null;
	self.state = null;
	self.phoneNumber = null;
	self.title = null;
	self.active = 'active';
	self.image = null;
	self.role = null;
	self.roles = [];

	self.formatName = function() {
		if (!launch.utils.isBlank(self.firstName) && !launch.utils.isBlank(self.lastName)) {
			return self.firstName + ' ' + self.lastName;
		}

		if (!launch.utils.isBlank(self.userName)) {
			return self.userName;
		}

		if (!launch.utils.isBlank(self.email)) {
			return self.email;
		}

		return self.id;
	};

	self.matchSearchTerm = function(term) {
		if (launch.utils.startsWith(self.userName, term) || launch.utils.startsWith(self.firstName, term) ||
			launch.utils.startsWith(self.lastName, term) || launch.utils.startsWith(self.email, term)) {
			return true;
		}

		return false;
	};

	self.hasImage = function() { return !launch.utils.isBlank(self.image); };
	self.imageUrl = function() { return self.hasImage() ? 'url(\'' + self.image + '\')' : null; };

	self.validateProperty = function(property) {
		switch (property) {
			case 'firstName':
				return launch.utils.isBlank(this.firstName) ? 'First Name is required.' : null;
			case 'lastName':
				return launch.utils.isBlank(this.lastName) ? 'Last Name is required.' : null;
			case 'email':
				if (launch.utils.isBlank(this.email)) {
					return 'Email is required.';
				} else if (!launch.utils.isValidEmail(this.email)) {
					return 'Please enter a valid email address.';
				}

				return null;
			case 'title':
				return launch.utils.isBlank(this.title) ? 'Title is required.' : null;
			case 'phoneNumber':
				return launch.utils.isBlank(this.phoneNumber) ? 'Phone is required.' : null;
			case 'active':
				return launch.utils.isBlank(this.active) ? 'Active Status is required.' : null;
			case 'role':
				return (!this.role || launch.utils.isBlank(this.role.roleName)) ? 'Role is required.' : null;
			default:
				return null;
		}
	};

	return self;
};