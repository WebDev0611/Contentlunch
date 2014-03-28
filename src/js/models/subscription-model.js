launch.Subscription = function(level) {
	var self = this;

	if (!level || isNaN(level) || level <= 0 || level > 3) {
		return null;
	}

	self.id = null;
	self.subscriptionLevel = parseInt(level);
	self.numberLicenses = 0;
	self.pricePerMonth = 0;
	self.annualDiscount = 0;
	self.training = true;
	self.features = null;
	self.created = null;
	self.updated = null;

	self.components = [];

	self.formatPricePerMonth = function (yearlyPayment) {
		var price = isNaN(self.pricePerMonth) ? 0 : parseFloat(self.pricePerMonth);

		if (yearlyPayment === true && parseInt(self.annualDiscount) > 0) {
			price = (price * (1 - (self.annualDiscount / 100)));
		}

		return parseFloat(price).toFixed(2);
	};

	self.validateProperty = function(property) {
		switch (property.toLowerCase()) {
			case 'pricepermonth':
				if (launch.utils.isBlank(this.pricePerMonth)) {
					return 'Price per Month is required.';
				} else if (isNaN(this.pricePerMonth) || parseFloat(this.pricePerMonth) <= 0) {
					return 'Price per Month must be a number greater than zero.';
				}

				return null;
			case 'annualdiscount':
				if (launch.utils.isBlank(this.annualDiscount)) {
					return 'Annual Discount is required.';
				} else if (isNaN(this.annualDiscount) || this.annualDiscount < 0) {
					return 'Annual Discount must be a number greater than or equal to zero.';
				}

				return null;
			case 'numberlicenses':
				if (launch.utils.isBlank(this.numberLicenses)) {
					return 'Number of Users is required.';
				} else if (isNaN(this.numberLicenses) || parseFloat(this.numberLicenses) <= 0) {
					return 'Number of Users must be a number greater than zero.';
				}

				return null;
			case 'training':
				if (!this.training) {
					this.training = false;
				}

				return null;
			default:
				return null;
		}
	};

	self.getName = function(prefix, suffix) {
		var name = 'Tier ' + self.subscriptionLevel;
		var spacer = '';

		if (!launch.utils.isBlank(prefix)) {
			spacer = launch.utils.isValidPattern(prefix, /\s+$/) ? '' : ' ';
			name = prefix + spacer + name;
		}

		if (!launch.utils.isBlank(suffix)) {
			spacer = launch.utils.isValidPattern(suffix, /^\s+/) ? '' : ' ';
			name = name + spacer + suffix;
		}

		return name;
	};

	return self;
};