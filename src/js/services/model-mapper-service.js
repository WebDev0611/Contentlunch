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
			var state = $.isPlainObject(dto.state) ? dto.state : launch.utils.getState(dto.country, dto.state);

			account.id = parseInt(dto.id);
			account.title = account.name = dto.title;
			account.active = (parseInt(dto.active) === 1) ? true : false;
			account.address1 = dto.address;
			account.address2 = dto.address_2;
			account.city = dto.city;
			account.state = (!!state) ? state : { value: dto.state, name: null };
			account.postalCode = dto.zipcode;
			account.country = dto.country;
			account.email = dto.email;
			account.phoneNumber = dto.phone;
			account.userCount = parseInt(dto.count_users);
			account.created = dto.created_at;
			account.updated = dto.updated_at;

			account.subscription = self.subscription.fromDto(dto.account_subscription);

			if ($.isArray(dto.modules) && dto.modules.length > 0) {
				account.subscription.components = $.map(dto.modules, function(m) {
					var module = self.module.fromDto(m);

					// TODO: REMOVE THIS STUFF ONCE THE "active" FLAG COMES FROM THE API!!
					if (module.name === 'collaborate') {
						module.active = account.subscription.subscriptionLevel >= 2;
					} else if (module.name === 'consult') {
						module.active = account.subscription.subscriptionLevel >= 3;
					} else {
						module.active = true;
					}

					return module;
				});
			}

			account.autoRenew = parseInt(dto.auto_renew) === 1 ? true : false;
			account.expirationDate = new Date(dto.expiration_date);
			account.paymentType = dto.payment_type;
			account.yearlyPayment = parseInt(dto.yearly_payment) === 1 ? true : false;
			account.hasToken = dto.hasToken;
			account.tokenizedType = dto.payment_type;

			if (!!dto.payment_info) {
				if (!launch.utils.isBlank(dto.payment_info.card_number)) {
					account.creditCard = new launch.CreditCard();
					account.creditCard.cardNumber = dto.payment_info.card_number;
					account.creditCard.nameOnCard = dto.payment_info.name_on_card;
					account.creditCard.cardType = dto.payment_info.card_type;
					account.creditCard.cvc = dto.payment_info.cvc;
					account.creditCard.expirationDateMonth = dto.payment_info.expiration_date_month;
					account.creditCard.expirationDateYear = dto.payment_info.expiration_date_year;
					account.creditCard.postalCode = dto.payment_info.postal_code;
				} else if (!launch.utils.isBlank(dto.payment_info.bank_name)) {
					account.bankAccount = new launch.BankAccount();
					account.bankAccount.bankName = dto.payment_info.bank_name;
					account.bankAccount.routingNumber = dto.payment_info.routing_number;
					account.bankAccount.accountNumber = dto.payment_info.account_number;
				}
			}

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
				hasToken: account.hasToken,
				created_at: account.created,
				updated_at: account.updated
			};

			if (!!account.creditCard && !launch.utils.isBlank(account.creditCard.cardNumber) && !launch.utils.isValidPattern(account.creditCard.cardNumber, /\*/)) {
				dto.payment_info = {
					card_number: '************' + account.creditCard.cardNumber.substr(12),
					name_on_card: account.creditCard.nameOnCard,
					card_type: account.creditCard.cardType,
					cvc: account.creditCard.cvc,
					expiration_date_month: account.creditCard.expirationDateMonth,
					expiration_date_year: account.creditCard.expirationDateYear,
					postal_code: account.creditCard.postalCode,
				};
			} else if (!!account.bankAccount && !launch.utils.isBlank(account.bankAccount.accountNumber) && !launch.utils.isValidPattern(account.bankAccount.accountNumber, /\*/)) {
				dto.payment_info = {
					bank_name: account.bankAccount.bankName,
					routing_number: account.bankAccount.routingNumber,
					account_number: '************' + account.bankAccount.accountNumber.substr(12)
				};
			}

			if (account.token) {
				dto.token = account.token;
			}

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
			account.hasToken = cachedAccount.hasToken;

			account.creditCard = new launch.CreditCard();
			//account.creditCard.cardNumber = null;
			//account.creditCard.nameOnCard = null;
			//account.creditCard.cardType = null;
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
			var state = launch.utils.getState(dto.country, dto.state);

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
			user.state = (!!state) ? state : { value: dto.state, name: null };
			user.phoneNumber = dto.phone;
			user.title = dto.title;
			user.userName = dto.username;
			user.active = (parseInt(dto.status) === 1) ? true : false;
			user.accounts = ($.isArray(dto.accounts)) ? $.map(dto.accounts, function(a, i) { return self.account.fromDto(a); }) : [];
			user.account = (user.accounts.length > 0) ? user.accounts[0] : null;
			user.roles = ($.isArray(dto.roles)) ? $.map(dto.roles, function (r, i) { return self.role.fromDto(r); }) : [];
			user.role = (user.roles.length > 0) ? user.roles[0] : null;

			if (dto.impersonating) {
				user.impersonating = true;
			}

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
				var isGlobalAdmin = (!!user && user.role.isGlobalAdmin === true) ? true : false;

				angular.forEach(dto, function(r, index) {
					var role = self.role.fromDto(r);

					if (isGlobalAdmin === role.isGlobalAdmin) {
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
		formatRequest: function(role) {
			return JSON.stringify(self.role.toDto(role));
		},
		fromDto: function(dto) {
			if (!dto) {
				return null;
			}

			var role = new launch.Role();

			role.id = parseInt(dto.id);
			role.name = dto.name;
			role.displayName = dto.display_name;
			role.active = parseInt(dto.status) === 1 ? true : false;
			role.isGlobalAdmin = parseInt(dto.global) === 1 ? true : false;
			role.isBuiltIn = parseInt(dto.builtin) === 1 ? true : false;
			role.isDeletable = parseInt(dto.deletable) === 1 ? true : false;
			role.accountId = parseInt(dto.account_id);
			role.created = new Date(dto.created_at);
			role.updated = new Date(dto.updated_at);

			// TODO: SET THE MODULES CORRECTLY FROM THE API WHEN IT'S READY!!
			role.modules = [
				{
					name: 'Home',
					mainNav: true,
					privileges: []
				},
				{
					name: 'Consult',
					mainNav: true,
					privileges: []
				},
				{
					name: 'Create',
					mainNav: true,
					privileges: [
						{ name: 'Create Section 1', view: true, edit: true, execute: true },
						{ name: 'Create Section 2', view: true, edit: true, execute: true },
						{ name: 'Create Section 3', view: true, edit: true, execute: true }
					]
				},
				{
					name: 'Collaborate',
					mainNav: true,
					privileges: [
						{ name: 'Collaborate Section 1', view: true, edit: true, execute: true },
						{ name: 'Collaborate Section 2', view: true, edit: true, execute: true },
						{ name: 'Collaborate Section 3', view: true, edit: true, execute: true }
					]
				},
				{
					name: 'Calendar',
					mainNav: true,
					privileges: [
						{ name: 'Calendar Section 1', view: true, edit: true, execute: true },
						{ name: 'Calendar Section 2', view: true, edit: true, execute: true },
						{ name: 'Calendar Section 3', view: true, edit: true, execute: true }
					]
				},
				{
					name: 'Launch',
					mainNav: true,
					privileges: [
						{ name: 'Launch Section 1', view: true, edit: true, execute: true },
						{ name: 'Launch Section 2', view: true, edit: true, execute: true },
						{ name: 'Launch Section 3', view: true, edit: true, execute: true }
					]
				},
				{
					name: 'Measure',
					mainNav: true,
					privileges: [
						{ name: 'Measure Section 1', view: true, edit: true, execute: true },
						{ name: 'Measure Section 2', view: true, edit: true, execute: true },
						{ name: 'Measure Section 3', view: true, edit: true, execute: true }
					]
				},
				{
					name: 'Admin/Settings',
					mainNav: false,
					privileges: [
						{ name: 'Account Settings', view: true, edit: true, execute: true },
						{ name: 'Content Connections', view: true, edit: true, execute: true },
						{ name: 'Content Settings', view: true, edit: true, execute: true },
						{ name: 'SEO Settings', view: true, edit: true, execute: true },
						{ name: 'Styles/Branding', view: true, edit: true, execute: true },
						{ name: 'Buyer Personas', view: true, edit: true, execute: true },
						{ name: 'Manage API/Plugins', view: true, edit: true, execute: true }
					]
				}
			];

			return role;
		},
		fromCache: function(cachedRole) {
			var role = new launch.Role();

			role.active = cachedRole.active;
			role.id = cachedRole.id;
			role.name = cachedRole.name;
			role.displayName = cachedRole.displayName;
			role.isGlobalAdmin = cachedRole.isGlobalAdmin;
			role.accountId = cachedRole.accountId;
			role.created = cachedRole.created;
			role.updated = cachedRole.updated;

			role.privileges = cachedRole.privileges;
			role.modules = cachedRole.modules;

			return role;
		},
		toDto: function (role) {
			var dto = {
				id: role.id,
				name: role.name,
				display_name: role.displayName,
				status: (role.active === true) ? 1 : 0,
				global: (role.isGlobalAdmin === true) ? 1 : 0,
				builtin: (role.isBuiltIn === true) ? 1 : 0,
				deletable: (role.isDeletable === true) ? 1 : 0,
				account_id: role.accountId,
				created_at: role.created,
				updated_at: role.updated
			};

			if (launch.utils.isBlank(dto.name)) {
				dto.name = dto.display_name.replace(/[\s~`!@#\$%\^&\*\(\)-\+=\{\}\[\]\|\\;:'",\.<>\?\/]/g, '_');
			}

			return dto;
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

			var subscription = new launch.Subscription(parseInt(dto.subscription_level));

			subscription.id = parseInt(dto.id);
			subscription.numberLicenses = parseInt(dto.licenses);
			subscription.pricePerMonth = parseFloat(dto.monthly_price);
			subscription.training = parseInt(dto.training) === 1 ? true : false;
			subscription.annualDiscount = parseFloat(dto.annual_discount);
			subscription.features = dto.features;
			subscription.created = new Date(dto.created_at);
			subscription.updated = new Date(dto.updated_at);

			// TODO: SET THE COMPONENTS CORRECTLY FROM THE API WHEN IT'S READY!!
			//			THIS IS ALREADY DONE WHEN GETTING MODULES FOR AN ACCOUNT (EXCEPT FOR THE "active" FLAG).
			//			STILL NEEDS TO BE DONE WHEN GETTING COMPONENTS FOR A SUBSCRIPTION AND FOR A USER ROLE.
			subscription.components = [
				{ name: 'create', title: 'CREATE', active: true },
				{ name: 'calendar', title: 'CALENDAR', active: true },
				{ name: 'launch', title: 'LAUNCH', active: true },
				{ name: 'measure', title: 'MEASURE', active: true },
				{ name: 'collaborate', title: 'COLLABORATE', active: subscription.subscriptionLevel >= 2 },
				{ name: 'consult', title: 'CONSULT', active: subscription.subscriptionLevel >= 3 }
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
				subscription_level: subscription.subscriptionLevel
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
			subscription.components = cachedSubscription.components;

			return subscription;
		}
	};

	self.module = {
		fromDto: function(dto) {
			if (!dto) {
				return null;
			}

			var module = new launch.Module();

			module.id = parseInt(dto.id);
			self.active = parseInt(dto.active) === 1;
			module.name = dto.name;
			module.title = dto.title;
			module.created = new Date(dto.created_at);
			module.updated = new Date(dto.updated_at);

			return module;
		},
		toDto: function(module) {
			return {
				id: parseInt(module.id),
				active: (module.active === true) ? 1 : 0,
				name: module.name,
				title: module.title,
				created: module.created_at,
				updated: module.updated_at
			};
		},
		fromCache: function(cachedModule) {
			if (!cachedModule) {
				return null;
			}

			var module = new launch.Module();

			module.id = parseInt(cachedModule.id);
			self.active = cachedModule.active;
			module.name = cachedModule.name;
			module.title = cachedModule.title;
			module.created = new Date(cachedModule.created_at);
			module.updated = new Date(cachedModule.updated_at);

			return module;
		}
	};

	return self;
};

launch.module.factory('ModelMapperService', function (AuthService, NotificationService) {
	return new launch.ModelMapper(AuthService, NotificationService);
});
