launch.Account = function() {
	var self = this;

	self.id = null;
	self.title = null;
	self.active = 'active';
	self.address1 = null;
	self.address2 = null;
	self.city = null;
	self.state = null;
	self.postalCode = null;
	self.country = null;
	self.email = null;
	self.phoneNumber = null;
	self.autoRenew = false;
	self.creditCard = null;
	self.created = null;
	self.updated = null;

	self.matchSearchTerm = function(term) {
		if (launch.utils.startsWith(self.title, term)) {
			return true;
		}

		return false;
	};

	self.validateProperty = function(property) {
		switch (property.toLowerCase()) {
			case 'title':
				return launch.utils.isBlank(this.title) ? 'Title is required.' : null;
			case 'email':
				if (launch.utils.isBlank(this.email)) {
					return 'Email is required.';
				} else if (!launch.utils.isValidEmail(this.email)) {
					return 'Please enter a valid email address.';
				}

				return null;
			case 'phonenumber':
				return launch.utils.isBlank(this.phoneNumber) ? 'Phone Number is required.' : null;
			case 'address1':
				return launch.utils.isBlank(this.address1) ? 'Address 1 is required.' : null;
			case 'city':
				return launch.utils.isBlank(this.city) ? 'City is required.' : null;
			case 'state':
				return launch.utils.isBlank(this.state) ? 'State is required.' : null;
			case 'postalcode':
				return launch.utils.isBlank(this.postalCode) ? 'Postal Code is required.' : null;
			case 'country':
				return launch.utils.isBlank(this.country) ? 'Country is required.' : null;
			case 'active':
				return launch.utils.isBlank(this.active) ? 'Active Status is required.' : null;
			default:
				return null;
		}
	};

	return this;
};