launch.Subscription = function(id, yearly, autoRenew) {
	var self = this;
	var price = 0;

	if (!id || isNaN(id) || id <= 0 || id > 3) {
		return null;
	}

	self.id = parseInt(id);

	self.numberLicenses = (self.id === 1 ? 5 : (self.id === 2 ? 10 : 20));
	self.yearlyPayment = (yearly === true) ? true : false;
	self.autoRenew = (autoRenew === true) ? true : false;
	self.training = true;
	self.features = (self.id === 1 ? [] : (self.id === 2 ? ['API', 'Premium Support'] : ['API', 'Premium Support', 'Custom Reporting', 'Advanced Security']));

	price = (self.id === 1 ? 300 : (self.id === 2 ? 500 : 700));

	if (self.yearlyPayment === true) {
		price = (price * 0.9);
	}

	self.pricePerMonth = launch.utils.formatCurrency(price);

	self.components = [
		{ name: 'create', title: 'CREATE', active: true },
		{ name: 'calendar', title: 'CALENDAR', active: true },
		{ name: 'launch', title: 'LAUNCH', active: true },
		{ name: 'measure', title: 'MEASURE', active: true },
		{ name: 'collaborate', title: 'COLLABORATE', active: self.id >= 2 },
		{ name: 'consult', title: 'CONSULT', active: self.id >= 3 }
	];

	return self;
};