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
	self.subscription = null;
	self.numberLicenses = null;
	self.accountExpirationDate = null;
	self.autoRenew = false;
	self.monthlyPayment = true;
	self.pricePerMonth = null;
	self.creditCard = null;
	self.created = null;
	self.updated = null;

	self.matchSearchTerm = function(term) {
		if (launch.utils.startsWith(self.title, term) || launch.utils.startsWith(self.email, term) ||
			launch.utils.startsWith(self.city, term) || launch.utils.startsWith(self.state.name, term) ||
			launch.utils.startsWith(self.state.value, term)) {
			return true;
		}

		return false;
	};

	self.validateProperty = function(property) {
		switch (property.toLowerCase()) {
			case 'title':
			case 'accountname':
				return launch.utils.isBlank(this.title) ? 'Account Name is required.' : null;
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
			case 'numberLicenses':
				if (launch.utils.isBlank(this.numberLicenses) || isNaN(this.numberLicenses)) {
					return 'Number of Users must be a number.';
				} else if (parseInt(this.numberLicenses) <= 0) {
					return 'Number of Users must be greater than zero.';
				}

				this.numberLicenses = parseInt(this.numberLicenses);

				return null;
			default:
				return null;
		}
	};

	self.formattedExpirationDate = function() {
		return launch.utils.formatDate(self.accountExpirationDate);
	};

	return self;
};