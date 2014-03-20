launch.Subscription = function(id) {
	var self = this;

	if (!id || isNaN(id) || id <= 0 || id > 3) {
		return null;
	}

	self.id = parseInt(id);
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
		if (typeof self.id === 'string') {
			self.id = parseInt(self.id);
		}

		self.features = (self.id === 1 ? [] : (self.id === 2 ? ['API', 'Premium Support'] : ['API', 'Premium Support', 'Custom Reporting', 'Advanced Security']));
		self.numberLicenses = (self.id === 1 ? 5 : (self.id === 2 ? 10 : 20));

		self.components = [
			{ name: 'create', title: 'CREATE', active: true },
			{ name: 'calendar', title: 'CALENDAR', active: true },
			{ name: 'launch', title: 'LAUNCH', active: true },
			{ name: 'measure', title: 'MEASURE', active: true },
			{ name: 'collaborate', title: 'COLLABORATE', active: self.id >= 2 },
			{ name: 'consult', title: 'CONSULT', active: self.id >= 3 }
		];

		return self.changePaymentPeriod();
	};

	self.changePaymentPeriod = function() {
		var price = (self.id === 1 ? 300 : (self.id === 2 ? 500 : 700));

		if (self.yearlyPayment === true) {
			price = (price * 0.9);
		}

		self.pricePerMonth = launch.utils.formatCurrency(price);

		return self;
	};

	self = self.changeTier();

	return self;
};