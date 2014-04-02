launch.module.factory('PaymentService', function ($window, ModelMapperService) {
	var balanced = $window.balanced;

	return {
		saveCreditCard: function(card, callback) {
			console.log('tokenize cc', card);

			var handler = (!!callback && $.isFunction(callback)) ? callback : (!!callback && $.isFunction(callback.success)) ? callback.success : null;

			balanced.card.create({
				name: card.nameOnCard,
				number: card.cardNumber,
				expiration_month: card.expirationDateMonth,
				expiration_year: card.expirationDateYear,
				security_code: card.cvc
			}, handler);
		},
		saveBankAccount: function (account, callback) {
			console.log('tokenize account', account);

			var handler = (!!callback && $.isFunction(callback)) ? callback : (!!callback && $.isFunction(callback.success)) ? callback.success : null;

			balanced.bankAccount.create({
				name: account.bankName,
				account_number: account.accountNumber,
				routing_number: account.routingNumber
			}, handler);
		}
	};
});