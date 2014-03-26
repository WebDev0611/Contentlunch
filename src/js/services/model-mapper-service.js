launch.ModelMapper = function (authService, notificationService) {
	var self = this;

	self.account = {
		parseResponse: function (r, getHeaders) {
			if (launch.utils.isBlank(r)) {
				return null;
			}

			var dto = JSON.parse(r);

			if (!!dto.error) {
				launch.utils.handleAjaxErrorResponse(dto.error, notificationService);
				return null;
			}

			if ($.isArray(dto)) {
				var accounts = [];

				angular.forEach(dto, function (account, index) {
					accounts.push(self.account.fromDto(account));
				});

				accounts.sort(self.account.sort);

				return accounts;
			}

			if ($.isPlainObject(dto)) {
				return self.account.fromDto(dto);
			}

			return null;
		},
		formatRequest: function(account) {
			return JSON.stringify(self.account.toDto(account));
		},
		fromDto: function (dto) {
			if (!dto) {
				return null;
			}

			var account = new launch.Account();

			account.id = parseInt(dto.id);
			account.title = account.name = dto.title;
			account.active = (parseInt(dto.active) === 1) ? true : false;
			account.address1 = dto.address;
			account.address2 = dto.address_2;
			account.city = dto.city;
			account.state = { value: dto.state, name: null };
			account.postalCode = dto.zipcode;
			account.country = dto.country;
			account.email = dto.email;
			account.phoneNumber = dto.phone;
			account.userCount = parseInt(dto.count_users);
			account.created = dto.created_at;
			account.updated = dto.updated_at;

			account.subscription = self.subscription.fromDto(dto.account_subscription);

			account.autoRenew = parseInt(dto.auto_renew) === 1 ? true : false;
			account.expirationDate = new Date(dto.expiration_date);
			account.paymentType = dto.payment_type;
			account.yearlyPayment = parseInt(dto.yearly_payment) === 1 ? true : false;

			account.creditCard = new launch.CreditCard();
			//account.creditCard.cardNumber = null;
			//account.creditCard.nameOnCard = null;
			//account.creditCard.cvc = null;
			//account.creditCard.expirationDateMonth = null;
			//account.creditCard.expirationDateYear = null;
			//account.creditCard.address1 = null;
			//account.creditCard.address2 = null;
			//account.creditCard.city = null;
			//account.creditCard.country = null;
			//account.creditCard.state = null;
			//account.creditCard.postalCode = null;

			account.bankAccount = new launch.BankAccount();
			//account.bankAccount.bankName = null;
			//account.bankAccount.routingNumber = null;
			//account.bankAccount.accountNumber = null;

			if (account.country === 'US') {
				account.country = 'USA';
			}

			return account;
		},
		toDto: function (account) {
			var dto = {
				id: account.id,
				title: account.title,
				name: account.title,
				active: (account.active === true) ? 1 : 0,
				address: account.address1,
				address_2: account.address2,
				city: account.city,
				state: (!!account.state) ? account.state.value : null,
				zipcode: account.postalCode,
				country: account.country,
				email: account.email,
				phone: account.phoneNumber,
				auto_renew: (account.autoRenew === true) ? 1 : 0,
				expiration_date: account.expirationDate,
				payment_type: account.paymentType,
				yearly_payment: (account.yearlyPayment === true) ? 1 : 0,
				created_at: account.created,
				updated_at: account.updated
			};

			return dto;
		},
		fromCache: function (cachedAccount) {
			if (!cachedAccount) {
				return null;
			}

			var account = new launch.Account();

			account.id = parseInt(cachedAccount.id);
			account.title = account.name = cachedAccount.title;
			account.active = cachedAccount.active;
			account.address1 = cachedAccount.address1;
			account.address2 = cachedAccount.address2;
			account.city = cachedAccount.city;
			account.state = cachedAccount.state;
			account.postalCode = cachedAccount.postalCode;
			account.country = cachedAccount.country;
			account.email = cachedAccount.email;
			account.phoneNumber = cachedAccount.phoneNumber;
			account.created = cachedAccount.created;
			account.updated = cachedAccount.updated;

			account.subscription = self.subscription.fromCache(cachedAccount.subscription);

			account.autoRenew = cachedAccount.autoRenew;
			account.expirationDate = new Date(cachedAccount.expirationDate);
			account.paymentType = cachedAccount.paymentType;
			account.yearlyPayment = account.yearlyPayment;

			account.creditCard = new launch.CreditCard();
			//account.creditCard.cardNumber = null;
			//account.creditCard.nameOnCard = null;
			//account.creditCard.cvc = null;
			//account.creditCard.expirationDateMonth = null;
			//account.creditCard.expirationDateYear = null;
			//account.creditCard.address1 = null;
			//account.creditCard.address2 = null;
			//account.creditCard.city = null;
			//account.creditCard.country = null;
			//account.creditCard.state = null;
			//account.creditCard.postalCode = null;

			account.bankAccount = new launch.BankAccount();
			//account.bankAccount.bankName = null;
			//account.bankAccount.routingNumber = null;
			//account.bankAccount.accountNumber = null;

			if (account.country === 'US') {
				account.country = 'USA';
			}

			return account;
		},
		sort: function (a, b) {
			if (a.title === b.title) {
				if (a.id === b.id) {
					return 0;
				} else if (a.id < b.id) {
					return -1;
				} else {
					return 1;
				}
			} else {
				if (a.title < b.title) {
					return -1;
				} else {
					return 1;
				}
			}
		}
	};

	self.user = {
		parseResponse: function(r, getHeaders) {
			if (launch.utils.isBlank(r)) {
				return null;
			}

			var dto = JSON.parse(r);

			if (!!dto.error) {
				launch.utils.handleAjaxErrorResponse(dto.error, notificationService);
				return null;
			}

			if ($.isArray(dto)) {
				var users = [];

				angular.forEach(dto, function (u, index) {
					users.push(self.user.fromDto(u));
				});

				users.sort(self.user.sort);

				return users;
			}

			if ($.isPlainObject(dto)) {
				return self.user.fromDto(dto);
			}

			return null;
		},
		formatRequest: function (user) {
			return JSON.stringify(self.user.toDto(user));
		},
		fromDto: function (dto) {
			if (!dto) {
				return null;
			}

			var user = new launch.User();

			user.id = parseInt(dto.id);
			user.userName = dto.userName;
			user.firstName = dto.first_name;
			user.lastName = dto.last_name;
			user.email = dto.email;
			user.created = dto.created_at;
			user.updated = dto.updated_at;
			user.confirmed = dto.confirmed;
			user.address1 = dto.address;
			user.address2 = dto.address_2;
			user.city = dto.city;
			user.country = dto.country;
			user.state = { value: dto.state, name: null };
			user.phoneNumber = dto.phone;
			user.title = dto.title;
			user.userName = dto.username;
			user.active = (parseInt(dto.status) === 1) ? true : false;
			user.accounts = ($.isArray(dto.accounts)) ? $.map(dto.accounts, function(a, i) { return self.account.fromDto(a); }) : [];
			user.account = (user.accounts.length > 0) ? user.accounts[0] : null;
			user.roles = ($.isArray(dto.roles)) ? $.map(dto.roles, function (r, i) { return self.role.fromDto(r); }) : [];
			user.role = (user.roles.length > 0) ? user.roles[0] : null;

			if (!!dto.image) {
				var path = dto.image.path;

				if (launch.utils.startsWith(path, '/public')) {
					path = path.substring(7);
				}

				user.image = path + '' + dto.image.filename;
			} else {
				user.image = null;
			}

			return user;
		},
		toDto: function(user) {
			var dto = {
				id: user.id,
				userName: user.userName,
				first_name: user.firstName,
				last_name: user.lastName,
				email: user.email,
				created_at: user.created,
				updated_at: user.updated,
				confirmed: user.confirmed,
				address: user.address1,
				address_2: user.address2,
				city: user.city,
				state: (!!user.state) ? user.state.value : null,
				country: user.country,
				phone: user.phoneNumber,
				title: user.title,
				status: (user.active === true) ? 1 : 0,
				accounts: $.map(user.accounts, function (a, i) { return self.account.toDto(a); }),
				roles: $.map(user.roles, function (r, i) { return self.role.toDto(r); })
			};

			if (!launch.utils.isBlank(user.password) && !launch.utils.isBlank(user.passwordConfirmation)) {
				dto.password = user.password;
				dto.password_confirmation = user.passwordConfirmation;
			}

			return dto;
		},
		fromCache: function(cachedUser) {
			if (!cachedUser) {
				return null;
			}

			var user = new launch.User();

			user.id = cachedUser.id;
			user.userName = cachedUser.userName;
			user.firstName = cachedUser.firstName;
			user.lastName = cachedUser.lastName;
			user.email = cachedUser.email;
			user.created = cachedUser.created;
			user.updated = cachedUser.updated;
			user.confirmed = cachedUser.confirmed;
			user.address1 = cachedUser.address1;
			user.address2 = cachedUser.address2;
			user.city = cachedUser.city;
			user.country = cachedUser.country;
			user.state = cachedUser.state;
			user.phoneNumber = cachedUser.phoneNumber;
			user.title = cachedUser.title;
			user.active = cachedUser.active;
			user.image = cachedUser.image;
			user.roles = $.map(cachedUser.roles, function(r, i) { return self.role.fromCache(r); });
			user.role = (user.roles.length > 0) ? user.roles[0] : null;
			user.accounts = $.map(cachedUser.accounts, function (a, i) { return self.account.fromCache(a); });
			user.account = (user.accounts.length > 0) ? user.accounts[0] : null;

			return user;
		},
		sort: function(a, b) {
			var firstA = launch.utils.isBlank(a.firstName) ? '' : a.firstName.toUpperCase();
			var firstB = launch.utils.isBlank(b.firstName) ? '' : b.firstName.toUpperCase();
			var lastA = launch.utils.isBlank(a.lastName) ? '' : a.lastName.toUpperCase();
			var lastB = launch.utils.isBlank(b.lastName) ? '' : b.lastName.toUpperCase();

			if (lastA === lastB) {
				if (firstA === firstB) {
					return 0;
				} else if (firstA < firstB) {
					return -1;
				} else {
					return 1;
				}
			} else {
				if (lastA < lastB) {
					return -1;
				} else {
					return 1;
				}
			}
		}
	};

	self.role = {
		parseResponse: function(r, getHeaders) {
			if (launch.utils.isBlank(r)) {
				return null;
			}

			var dto = JSON.parse(r);

			if (!!dto.error) {
				launch.utils.handleAjaxErrorResponse(dto.error, notificationService);
				return null;
			}

			if ($.isArray(dto)) {
				var roles = [];
				var user = authService.userInfo();
				var isGlobalAdmin = (!!user) ? user.isGlobalAdmin() : false;

				angular.forEach(dto, function(r, index) {
					var role = self.role.fromDto(r);

					if (isGlobalAdmin === role.isGlobalAdmin()) {
						roles.push(role);
					}
				});

				roles.sort(self.role.sort);

				return roles;
			}

			if ($.isPlainObject(dto)) {
				return self.role.fromDto(dto);
			}

			return null;
		},
		formatRequest: function (role) {
			return JSON.stringify(self.role.toDto(role));
		},
		fromDto: function (dto) {
			if (!dto) {
				return null;
			}

			var role = new launch.Role();

			// TODO: SET ACTIVE STATUS FROM DTO WHEN THIS IS ADDED TO THE API!
			role.active = true;
			role.id = parseInt(dto.id);
			role.name = dto.name;
			role.created = dto.created_at;
			role.updated = dto.updated_at;

			// TODO: SET THE PRIVILEGES CORRECTLY FROM THE API WHEN IT'S READY!!
			role.privileges = [
				{ module: 'Consult', view: true, edit: true, execute: true },
				{ module: 'Create', view: true, edit: true, execute: true },
				{ module: 'Collaborate', view: true, edit: true, execute: true },
				{ module: 'Calendar', view: true, edit: true, execute: true },
				{ module: 'Launch', view: true, edit: true, execute: true },
				{ module: 'Measure', view: true, edit: true, execute: true }
			];

			return role;
		},
		fromCache: function(cachedRole) {
			var role = new launch.Role();

			role.active = cachedRole.active;
			role.id = cachedRole.id;
			role.name = cachedRole.name;
			role.created = cachedRole.created;
			role.updated = cachedRole.updated;

			role.privileges = cachedRole.privileges;

			return role;
		},
		toDto: function(role) {
			return {
				id: role.id,
				name: role.name,
				created_at: role.created,
				updated_at: role.updated
			};
		},
		sort: function(a, b) {
			var roleA = launch.utils.isBlank(a.name) ? '' : a.name.toUpperCase();
			var roleB = launch.utils.isBlank(b.name) ? '' : b.name.toUpperCase();

			if (roleA === roleB) {
				if (a.id === b.id) {
					return 0;
				} else if (a.id < b.id) {
					return -1;
				} else {
					return 1;
				}
			} else {
				if (roleA < roleB) {
					return -1;
				} else {
					return 1;
				}
			}
		}
	};

	self.subscription = {
		parseResponse: function(r, getHeaders) {
			if (launch.utils.isBlank(r)) {
				return null;
			}

			var dto = JSON.parse(r);

			if (!!dto.error) {
				launch.utils.handleAjaxErrorResponse(dto.error, notificationService);
				return null;
			}

			if ($.isArray(dto)) {
				var subscriptions = [];

				angular.forEach(dto, function (s, index) {
					subscriptions.push(self.subscription.fromDto(s));
				});

				subscriptions.sort(self.subscription.sort);

				return subscriptions;
			}

			if ($.isPlainObject(dto)) {
				return self.subscription.fromDto(dto);
			}

			return null;
		},
		formatRequest: function(subscription) {
			return JSON.stringify(self.subscription.toDto(subscription));
		},
		fromDto: function (dto) {
			if (!dto) {
				return null;
			}

			var id = (!!dto.subscription_id) ? dto.subscription_id : dto.id;
			// TODO: REPLACE THIS WITH "subscription_level" ONCE IT COMES OUT OF THE API!!!
			var subscription = new launch.Subscription(id);

			subscription.id = parseInt(dto.id);
			subscription.numberLicenses = parseInt(dto.licenses);
			subscription.pricePerMonth = parseFloat(dto.monthly_price);
			subscription.training = parseInt(dto.training) === 1 ? true : false;
			subscription.annualDiscount = parseFloat(dto.annual_discount);
			subscription.features = dto.features;
			subscription.created = new Date(dto.created_at);
			subscription.updated = new Date(dto.updated_at);

			// TODO: SET THE COMPONENTS CORRECTLY FROM THE API WHEN IT'S READY!!
			subscription.components = [
				{ name: 'create', title: 'CREATE', active: true },
				{ name: 'calendar', title: 'CALENDAR', active: true },
				{ name: 'launch', title: 'LAUNCH', active: true },
				{ name: 'measure', title: 'MEASURE', active: true },
				{ name: 'collaborate', title: 'COLLABORATE', active: self.subscriptionLevel >= 2 },
				{ name: 'consult', title: 'CONSULT', active: self.subscriptionLevel >= 3 }
			];

			return subscription;
		},
		toDto: function (subscription) {
			return {
				subscription_id: subscription.id,
				id: subscription.id,
				licenses: subscription.numberLicenses,
				monthly_price: subscription.pricePerMonth,
				training: subscription.training,
				annual_discount: subscription.annualDiscount,
				features: subscription.features,
				subscription: subscription.subscriptionLevel
			};
		},
		fromCache: function(cachedSubscription) {
			if (!cachedSubscription) {
				return null;
			}

			var subscription = new launch.Subscription(cachedSubscription.subscriptionLevel);

			subscription.id = cachedSubscription.id;
			subscription.numberLicenses = parseInt(cachedSubscription.numberLicenses);
			subscription.pricePerMonth = cachedSubscription.pricePerMonth;
			subscription.training = cachedSubscription.training;
			subscription.annualDiscount = cachedSubscription.annualDiscount;
			subscription.features = cachedSubscription.features;
			subscription.created = new Date(cachedSubscription.created);
			subscription.updated = new Date(cachedSubscription.updated);

			return subscription;
		}
	};

	return self;
};

launch.module.factory('ModelMapperService', function (AuthService, NotificationService) {
	return new launch.ModelMapper(AuthService, NotificationService);
});