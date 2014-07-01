launch.CreditCard = function() {
	var self = this;

	self.cardNumber = null;
	self.nameOnCard = null;
	self.cardType = null;
	self.cvc = null;
	self.expirationDateMonth = null;
	self.expirationDateYear = null;
	self.postalCode = null;

	self.validateProperty = function (property) {
		if (launch.utils.isValidPattern(this.cardNumber, /\*/)) {
			return null;
		}

		switch (property.toLowerCase()) {
			case 'cardnumber':
				if (launch.utils.isBlank(this.cardNumber)) { return 'Card Number is required.'; }
				if (isNaN(this.cardNumber) || (this.cardType !== 'AMEX' && this.cardNumber.length !== 16) || (this.cardType === 'AMEX' && this.cardNumber.length !== 15)) { return 'Card Number must be a number with ' + (this.cardType === 'AMEX' ? '15' : '16') + ' digits.'; }

				return null;
			case 'nameoncard':
				return launch.utils.isBlank(this.nameOnCard) ? 'Name on Card is required.' : null;
			case 'cardtype':
				if (launch.utils.isBlank(this.cardType)) { return 'Card Type is required.'; }
				if (this.cardType !== 'VISA' && this.cardType !== 'MASTERCARD' && this.cardType !== 'AMEX' && this.cardType !== 'DISCOVER') {
					return 'Unknown Card Type. Card Type must be Visa, MasterCard, American Express, or Discover.';
				}

				return null;
			case 'cvc':
				if (launch.utils.isBlank(this.cvc)) { return 'CVC is required.'; }
				if (isNaN(this.cvc) || (this.cardType !== 'AMEX' && (parseInt(this.cvc) < 100 || parseInt(this.cvc) > 999)) 
									|| (this.cardType === 'AMEX' && (parseInt(this.cvc) < 1000 || parseInt(this.cvc) > 9999))) { return 'CVC must be a number between ' + (this.cardType === 'AMEX' ? '1000 and 9999' : '100 and 999') + '.'; }

				return null;
			case 'expirationdatemonth':
				if (launch.utils.isBlank(this.expirationDateMonth)) { return 'Expiration Date Month is required.'; }
				if (isNaN(this.expirationDateMonth) || parseInt(this.expirationDateMonth) < 1 || parseInt(this.expirationDateMonth) > 12) { return 'Expiration Date Month must be a number between 1 and 12.'; }
				return null;
			case 'expirationdateyear':
				if (launch.utils.isBlank(this.expirationDateYear)) { return 'Expiration Date Year is required.'; }

				var minYear = (new Date()).getFullYear();
				var maxYear = minYear + 10;

				if (isNaN(this.expirationDateYear) || parseInt(this.expirationDateYear) < minYear || parseInt(this.expirationDateYear) > maxYear) {
					return 'Expiration Date Month must be a number between ' + minYear + ' and ' + maxYear + '.';
				}

				return null;
			case 'postalcode':
				if (launch.utils.isBlank(this.postalCode)) { return 'Postal Code is required.'; }

				return null;
			default:
				return null;
		}
	}

	return self;
};