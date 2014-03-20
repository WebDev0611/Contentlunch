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

	self.created = null;
	self.updated = null;

	self.formattedExpirationDate = function () {
		return launch.utils.isBlank(self.expirationDate) ? null : launch.utils.formatDate(self.expirationDate);
	};

	self.changeTier = function () {
		if (typeof self.subscriptionLevel === 'string') {
			self.subscriptionLevel = parseInt(self.subscriptionLevel);
		}

		self.features = (self.subscriptionLevel === 1 ? [] : (self.subscriptionLevel === 2 ? ['API', 'Premium Support'] : ['API', 'Premium Support', 'Custom Reporting', 'Advanced Security']));
		self.numberLicenses = (self.subscriptionLevel === 1 ? 5 : (self.subscriptionLevel === 2 ? 10 : 20));

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

		if (self.yearlyPayment === true) {
			price = (price * 0.9);
		}

		self.pricePerMonth = launch.utils.formatCurrency(price);

		return self;
	};

	self = self.changeTier();

	return self;
};