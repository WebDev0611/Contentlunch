launch.BankAccount = function () {
	var self = this;

	self.bankName = null;
	self.routingNumber = null;
	self.accountNumber = null;

	self.validateProperty = function(property) {
		if (launch.utils.isValidPattern(this.accountNumber, /\*/)) {
			return null;
		}

		switch (property.toLowerCase()) {
			case 'bankname':
				return launch.utils.isBlank(this.bankName) ? 'Bank Name is required.' : null;
			case 'routingnumber':
				if (launch.utils.isBlank(this.routingNumber)) { return 'Routing Number is required.'; }
				if (isNaN(this.routingNumber)) { return 'Routing Number must be a number.'; }

				return null;
			case 'accountnumber':
				if (launch.utils.isBlank(this.accountNumber)) { return 'Account Number is required.'; }
				if (isNaN(this.accountNumber)) { return 'Account Number must be a number.'; }

				return null;
			default:
				return null;
		};
	};

	return self;
};