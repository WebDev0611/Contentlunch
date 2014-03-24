launch.Subscription = function(level) {
	var self = this;

	if (!level || isNaN(level) || level <= 0 || level > 3) {
		return null;
	}

	self.id = null;
	self.subscriptionLevel = parseInt(level);
	self.autoRenew = false;
	self.expirationDate = null;
	self.paymentType = 'CC';
	self.training = true;
	self.yearlyPayment = false;
	self.numberLicenses = 5;
	self.pricePerMonth = 0;
	self.annualDiscount = 10;

	self.created = null;
	self.updated = null;

	self.formattedExpirationDate = function () {
		return launch.utils.isBlank(self.expirationDate) ? null : launch.utils.formatDate(self.expirationDate);
	};

	self.changeTier = function () {
		if (typeof self.subscriptionLevel === 'string') {
			self.subscriptionLevel = parseInt(self.subscriptionLevel);
		}

		self.numberLicenses = (self.subscriptionLevel === 1 ? 5 : (self.subscriptionLevel === 2 ? 10 : 20));
		self.annualDiscount = 10;

		self.features = (self.subscriptionLevel === 1 ? null : (self.subscriptionLevel === 2 ? 'API, Premium Support' : 'API, Premium Support, Custom Reporting, Advanced Security'));

		self.components = [
			{ name: 'create', title: 'CREATE', active: true },
			{ name: 'calendar', title: 'CALENDAR', active: true },
			{ name: 'launch', title: 'LAUNCH', active: true },
			{ name: 'measure', title: 'MEASURE', active: true },
			{ name: 'collaborate', title: 'COLLABORATE', active: self.subscriptionLevel >= 2 },
			{ name: 'consult', title: 'CONSULT', active: self.subscriptionLevel >= 3 }
		];

		return self.changePaymentPeriod();
	};

	self.changePaymentPeriod = function() {
		var price = (self.subscriptionLevel === 1 ? 300 : (self.subscriptionLevel === 2 ? 500 : 700));

		if (self.yearlyPayment === true && parseInt(self.annualDiscount) > 0) {
			price = (price * (1 - (self.annualDiscount / 100)));
		}

		self.pricePerMonth = parseFloat(price).toFixed(2);

		return self;
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

	self = self.changeTier();

	return self;
};