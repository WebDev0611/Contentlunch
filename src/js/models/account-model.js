﻿launch.Account = function() {
	var self = this;

	self.id = null;
	self.title = null;
	self.active = true;
	self.address1 = null;
	self.address2 = null;
	self.city = null;
	self.state = null;
	self.postalCode = null;
	self.country = null;
	self.email = null;
	self.phoneNumber = null;
	self.subscription = null;
	self.creditCard = null;
	self.bankAccount = null;
	self.userCount = 0;

	self.autoRenew = false;
	self.expirationDate = null;
	self.paymentType = 'CC';
	self.yearlyPayment = false;
	self.hasToken = false;

	self.created = null;
	self.updated = null;

	self.formattedExpirationDate = function () {
		return launch.utils.isBlank(self.expirationDate) ? null : launch.utils.formatDate(self.expirationDate);
	};

	self.matchSearchTerm = function(term) {
		if (launch.utils.startsWith(self.title, term) || launch.utils.startsWith(self.email, term) ||
			launch.utils.startsWith(self.city, term) ||
			launch.utils.startsWith(self.state.name, term) || launch.utils.startsWith(self.state.value, term)) {
			return true;
		}

		if (!!self.subscription) {
			var level = !isNaN(term) ? term : term.replace(/^tier /, '').replace(/^tier/, '').replace(/^tie/, '').replace(/^ti/, '').replace(/^t/, '');

			return self.subscription.subscriptionLevel === parseInt(level);
		}

		return false;
	};

	self.validateProperty = function(property) {
		switch (property.toLowerCase()) {
			case 'title':
			case 'accountname':
				return launch.utils.isBlank(this.name) ? 'Account Name is required.' : null;
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
				if (!launch.utils.isBlank(this.country) && (this.country.toUpperCase() === 'UK' || this.country.toUpperCase() === 'AUSTRALIA')) {
					return null;
				}

				return launch.utils.isBlank(this.state) ? 'State is required.' : null;
			case 'postalcode':
				return launch.utils.isBlank(this.postalCode) ? 'Postal Code is required.' : null;
			case 'country':
				return launch.utils.isBlank(this.country) ? 'Country is required.' : null;
			case 'active':
				if (typeof this.active !== 'boolean') { this.active = (this.active === 1 || this.active === '1' || this.active.toLowerCase() === 'true'); }

				return (this.active === true || this.active === false) ? null : 'Active Status is required.';
			case 'subscription':
				return (!this.subscription) ? 'Subscription is required.' : null;
			case 'paymenttype':
				if (launch.utils.isBlank(this.paymentType)) {
					return 'Payment Type is required.';
				}

				if (this.paymentType === 'CC') {
					if (!this.creditCard) { return 'Credit Card or Bank Account information is required.'; }

					if (!this.id || parseInt(this.id <= 0)) {
						if (launch.utils.isValidPattern(this.creditCard.cardNumber, /\*/)) {
							return 'Please enter a valid Credit Card number.';
						}
					}

					return launch.utils.validateAll(this.creditCard);
				}

				if (this.paymentType === 'ACH') {
					if (!this.bankAccount) { return 'Credit Card or Bank Account information is required.'; }

					if (!this.id || parseInt(this.id <= 0)) {
						if (launch.utils.isValidPattern(this.bankAccount.accountNumber, /\*/)) {
							return 'Please enter a valid Bank Account number.';
						}
					}

					return launch.utils.validateAll(this.bankAccount);
				}

				return 'Unknown Payment Type. Payment type must be Credit Card or ACH.';
			default:
				return null;
		}
	};

	return self;
};
