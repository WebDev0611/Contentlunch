launch.ModelMapper = function($location, authService, notificationService) {
	var self = this;

	self.auth = {
		parseResponse: function (r, getHeaders) {
			if (launch.utils.isBlank(r)) {
				return null;
			}

			var dto = JSON.parse(r);

			if (!!dto.error || !!dto.errors) {
				return dto;
			}

			if ($.isPlainObject(dto)) {
				return self.auth.fromDto(dto);
			}

			return null;
		},
		fromDto: function(dto) {
			if (launch.utils.isBlank(dto.id)) {
				return null;
			}
			var user = self.user.fromDto(dto);
			var auth = new launch.Authentication();

			auth.id = user.id;
			auth.displayName = user.formatName();
			auth.email = user.email;
			auth.phoneNumber = user.phoneNumber;
			auth.confirmed = user.confirmed;
			auth.active = user.active;
			auth.image = user.image;
			auth.account = user.account;
			auth.role = user.role;
			auth.created = user.created;
			auth.updated = user.updated;

			auth.modules = $.map(dto.modules, function(m) {
				var module = self.module.fromDto(m);

				if (!!dto.permissions) {
					module.privileges = $.map($.grep(dto.permissions, function (p) {
						return p.module.toLowerCase() === module.name;
					}), function (p) {
						return self.privilege.fromDto(p);
					});

					if (module.privileges.length === 0) {
						return null;
					}
				}

				return module;
			});

			return auth;
		},
		fromCache: function(cachedAuth) {
			var auth = new launch.Authentication();

			auth.id = cachedAuth.id;
			auth.displayName = cachedAuth.displayName;
			auth.email = cachedAuth.email;
			self.phoneNumber = cachedAuth.phoneNumber;
			auth.confirmed = cachedAuth.confirmed;
			auth.active = cachedAuth.active;
			auth.image = cachedAuth.image;
			auth.account = cachedAuth.account;
			auth.role = cachedAuth.role;
			auth.created = cachedAuth.created;
			auth.updated = cachedAuth.updated;

			auth.modules = cachedAuth.modules;

			return auth;
		}
	};

	self.account = {
		parseResponse: function(r, getHeaders) {
			if (launch.utils.isBlank(r)) {
				return null;
			}

			var dto = JSON.parse(r);

			if (!!dto.error || !!dto.errors) {
				return dto;
			}

			if ($.isArray(dto)) {
				var accounts = [];

				$.each(dto, function(index, account) {
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
		fromDto: function(dto) {
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
		toDto: function(account) {
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
		fromCache: function(cachedAccount) {
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
		sort: function(a, b) {
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

			if (!!dto.error || !!dto.errors) {
				return dto;
			}

			if ($.isArray(dto)) {
				var users = [];

				$.each(dto, function (index, u) {
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
		formatRequest: function(user) {
			return JSON.stringify(self.user.toDto(user));
		},
		fromDto: function(dto) {
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
			user.created = new Date(dto.created_at);
			user.updated = new Date(dto.updated_at);
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
			user.roles = ($.isArray(dto.roles)) ? $.map(dto.roles, function(r, i) { return self.role.fromDto(r); }) : [];
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
				accounts: ($.isArray(user.accounts)) ? $.map(user.accounts, function(a, i) { return self.account.toDto(a); }) : null,
				roles: $.isArray(user.roles) ? $.map(user.roles, function(r, i) { return self.role.toDto(r); }) : null
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

			if (!$.isPlainObject(cachedUser)) {
				cachedUser = JSON.parse(cachedUser);
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
			user.accounts = $.map(cachedUser.accounts, function(a, i) { return self.account.fromCache(a); });
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

			if (!!dto.error || !!dto.errors) {
				return dto;
			}

			if ($.isArray(dto)) {
				var roles = [];
				var user = authService.userInfo();
				var isGlobalAdmin = (!!user && user.role.isGlobalAdmin === true) ? true : false;

				$.each(dto, function (index, rl) {
					var role = self.role.fromDto(rl);

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

			if ($.isArray(dto.permissions)) {
				var readPrivs = [];
				var execPrivs = [];
				var mergePrivs = function(module) {
					var read = $.grep(readPrivs, function(p) { return (p.module === module); });
					var exec = $.grep(execPrivs, function(p) { return (p.module === module); });

					return $.merge(read, exec).sort(self.privilege.sort);
				};

				$.each(dto.permissions, function(i, p) {
					if (p.type === 'view') {
						var viewPrivilege = self.privilege.fromDto(p);
						var editPrivilege = $.grep(dto.permissions, function(ep) { return ep.name === p.name.replace('view', 'edit'); });

						if (editPrivilege.length === 1 && parseInt(editPrivilege[0].access) === 1) {
							viewPrivilege.edit = true;
						}

						readPrivs.push(viewPrivilege);
					} else if (p.type === 'execute') {
						execPrivs.push(self.privilege.fromDto(p));
					}
				});

				// TODO: THESE SHOULD COME FROM THE API!!
				var home = new launch.Module();
				var consult = new launch.Module();
				var create = new launch.Module();
				var collaborate = new launch.Module();
				var calendar = new launch.Module();
				var launchModule = new launch.Module();
				var measure = new launch.Module();
				var settings = new launch.Module();

				home.name = 'home';
				home.active = true;
				home.title = 'Home';
				home.created = null;
				home.updated = null;
				home.privileges = mergePrivs('home');

				consult.name = 'consult';
				consult.active = false;
				consult.title = 'Consult';
				consult.created = null;
				consult.updated = null;
				consult.privileges = mergePrivs('consult');

				create.name = 'create';
				create.active = false;
				create.title = 'Create';
				create.created = null;
				create.updated = null;
				create.privileges = mergePrivs('create');

				collaborate.name = 'collaborate';
				collaborate.active = false;
				collaborate.title = 'Collaborate';
				collaborate.created = null;
				collaborate.updated = null;
				collaborate.privileges = mergePrivs('collaborate');

				calendar.name = 'calendar';
				calendar.active = false;
				calendar.title = 'Calendar';
				calendar.created = null;
				calendar.updated = null;
				calendar.privileges = mergePrivs('calendar');

				launchModule.name = 'launch';
				launchModule.active = false;
				launchModule.title = 'Launch';
				launchModule.created = null;
				launchModule.updated = null;
				launchModule.privileges = mergePrivs('launch');

				measure.name = 'measure';
				measure.active = false;
				measure.title = 'Measure';
				measure.created = null;
				measure.updated = null;
				measure.privileges = mergePrivs('measure');

				settings.name = 'settings';
				settings.active = false;
				settings.title = 'Admin/Settings';
				settings.created = null;
				settings.updated = null;
				settings.privileges = mergePrivs('settings');

				role.modules = [home, consult, create, collaborate, calendar, launchModule, measure, settings];
			}

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
			role.modules = [];

			$.each(cachedRole.modules, function(i, m) {
				var module = new launch.Module();

				role.modules.push({ name: m.name, privileges: $.map(m, self.privilege.fromCache) });
			});

			return role;
		},
		toDto: function(role) {
			var dto = {
				id: role.id,
				name: role.name,
				display_name: role.displayName,
				global: (role.isGlobalAdmin === true) ? 1 : 0,
				deletable: (role.isDeletable === true) ? 1 : 0,
				builtin: (role.isBuiltIn === true) ? 1 : 0,
				status: (role.active === true) ? 1 : 0,
				account_id: role.accountId,
				created_at: role.created,
				updated_at: role.updated,
				permissions: []
			};

			if (launch.utils.isBlank(dto.name)) {
				dto.name = dto.display_name.replace(/[\s~`!@#\$%\^&\*\(\)-\+=\{\}\[\]\|\\;:'",\.<>\?\/]/g, '_');
			}

			$.each(role.modules, function(i, m) {
				var view = $.grep(m.privileges, function(p) { return (p.accessType != 'execute'); });
				var edit = $.grep(m.privileges, function(p) { return (p.accessType != 'execute'); });
				var exec = $.grep(m.privileges, function(p) { return (p.accessType == 'execute'); });

				$.merge(dto.permissions, $.map(view, function(p) { return self.privilege.toDto(p, 'view'); }));
				$.merge(dto.permissions, $.map(edit, function(p) { return self.privilege.toDto(p, 'edit', p.name.replace('_view_', '_edit_')); }));
				$.merge(dto.permissions, $.map(exec, function(p) { return self.privilege.toDto(p, 'execute'); }));
			});

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

			if (!!dto.error || !!dto.errors) {
				return dto;
			}

			if ($.isArray(dto)) {
				var subscriptions = [];

				$.each(dto, function (index, s) {
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
		fromDto: function(dto) {
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
		toDto: function(subscription) {
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

			if (!!dto.error || !!dto.errors) {
				return dto;
			}

			var module = new launch.Module();

			module.id = parseInt(dto.id);
			self.active = parseInt(dto.active) === 1;
			module.name = dto.name;
			module.title = dto.title;
			module.isSubscribable = dto.subscribable == true;
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
				subscribable: module.isSubscribable,
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

	self.privilege = {
		fromDto: function(dto) {
			var privilege = new launch.Privilege();
			var accessType = dto.type.toLowerCase();

			privilege.name = dto.name;
			privilege.displayName = dto.display_name;
			privilege.module = dto.module;

			privilege.accessType = (accessType === 'execute') ? 'execute' : null;
			privilege.view = (accessType === 'view' && parseInt(dto.access) === 1);
			privilege.edit = (accessType === 'edit' && parseInt(dto.access) === 1);
			privilege.execute = (accessType === 'execute' && parseInt(dto.access) === 1);

			return privilege;
		},
		fromCache: function(cachedPrivilege) {
			var privilege = new launch.Privilege();

			privilege.name = cachedPrivilege.name;
			privilege.displayName = cachedPrivilege.displayName;
			privilege.module = cachedPrivilege.module;
			privilege.accessType = cachedPrivilege.accessType;

			privilege.view = (cachedPrivilege.view == true);
			privilege.edit = (cachedPrivilege.edit == true);
			privilege.execute = (cachedPrivilege.execute == true);

			return privilege;
		},
		toDto: function(privilege, accessType, name) {
			var dto = {
				name: launch.utils.isBlank(name) ? privilege.name : name,
				display_name: privilege.displayName,
				access: (((accessType === 'view' && privilege.view) || (accessType === 'edit' && privilege.edit) || (accessType === 'execute' && privilege.execute)) ? 1 : 0),
				module: privilege.module,
				type: accessType
			};

			return dto;
		},
		sort: function(a, b) {
			if (!a && !b) {
				return 0;
			} else if (!a && !!b) {
				return 1;
			} else if (!!a && !b) {
				return -1;
			}

			var privA = launch.utils.isBlank(a.displayName) ? '' : a.displayName.toLowerCase();
			var privB = launch.utils.isBlank(b.displayName) ? '' : b.displayName.toLowerCase();

			if (privA === privB) {
				return 0;
			}

			var moduleA = a.module.toLowerCase();
			var moduleB = b.module.toLowerCase();

			if (moduleA === moduleB) {
				if (privA < privB) {
					return -1;
				} else {
					return 1;
				}
			} else {
				if (moduleA === 'home') {
					return -1;
				}
				if (moduleB === 'home') {
					return 1;
				}
				if (moduleA === 'consult') {
					return -1;
				}
				if (moduleB === 'consult') {
					return 1;
				}
				if (moduleA === 'create') {
					return -1;
				}
				if (moduleB === 'create') {
					return 1;
				}
				if (moduleA === 'collaborate') {
					return -1;
				}
				if (moduleB === 'collaborate') {
					return 1;
				}
				if (moduleA === 'calendar') {
					return -1;
				}
				if (moduleB === 'calendar') {
					return 1;
				}
				if (moduleA === 'launch') {
					return -1;
				}
				if (moduleB === 'launch') {
					return 1;
				}
				if (moduleA === 'measure') {
					return -1;
				}
				if (moduleB === 'measure') {
					return 1;
				}
				if (moduleA === 'admin') {
					return -1;
				}
				if (moduleB === 'admin') {
					return 1;
				}

				if (moduleA < moduleB) {
					return -1;
				} else {
					return 1;
				}
			}
		}
	};

	self.contentSettings = {
		parseResponse: function (r, getHeaders) {
			if (launch.utils.isBlank(r)) {
				return null;
			}

			var dto = JSON.parse(r);

			if (!!dto.error || !!dto.errors) {
				return dto;
			}

			if ($.isPlainObject(dto)) {
				return self.contentSettings.fromDto(dto);
			}

			return null;
		},
		formatRequest: function (settings) {
			return JSON.stringify(self.contentSettings.toDto(settings));
		},
		fromDto: function(dto) {
			var settings = new launch.ContentSettings();

			settings.id = parseInt(dto.id);
			settings.accountId = parseInt(dto.account_id);

			if ($.isPlainObject(dto.include_name)) {
				settings.includeAuthorName = parseInt(dto.include_name.enabled) === 1 ? true : false;
				settings.authorNameContentTypes = dto.include_name.content_types;
			} else {
				settings.includeAuthorName = false;
				settings.authorNameContentTypes = [];
			}

			if ($.isPlainObject(dto.allow_edit_date)) {
				settings.allowPublishDateEdit = parseInt(dto.allow_edit_date.enabled) === 1 ? true : false;
				settings.publishDateContentTypes = dto.allow_edit_date.content_types;
			} else {
				settings.allowPublishDateEdit = false;
				settings.publishDateContentTypes = [];
			}

			if ($.isPlainObject(dto.keyword_tags)) {
				settings.useKeywordTags = parseInt(dto.keyword_tags.enabled) === 1 ? true : false;
				settings.keywordTagsContentTypes = dto.keyword_tags.content_types;
			} else {
				settings.useKeywordTags = false;
				settings.keywordTagsContentTypes = [];
			}

			settings.publishingGuidelines = dto.publishing_guidelines;

			settings.personaProperties = $.map(dto.persona_columns, function (p) { return launch.utils.titleCase(p); });
			settings.personas = $.map(dto.personas, function (p) {
				return {
					name: p.name,
					properties: p.columns
				};
			});

			settings.created = new Date(dto.created_at);
			settings.updated = new Date(dto.updated_at);

			return settings;
		},
		toDto: function(settings) {
			return {
				id: settings.id,
				account_id: settings.accountId,
				include_name: { enabled: settings.includeAuthorName ? 1 : 0, content_types: settings.authorNameContentTypes },
				allow_edit_date: { enabled: settings.allowPublishDateEdit ? 1 : 0, content_types: settings.publishDateContentTypes },
				keyword_tags: { enabled: settings.useKeywordTags ? 1 : 0, content_types: settings.keywordTagsContentTypes },
				publishing_guidelines: settings.publishingGuidelines,
				persona_columns: $.map(settings.personaProperties, function(p) { return p.toLowerCase(); }),
				personas: $.map(settings.personas, function(p) { return { name: p.name, columns: p.properties } }),
				created_at: settings.created,
				updated_at: settings.updated
			};
		}
	};

	self.contentConnection = {
		parseResponse: function(r, getHeaders) {
			if (launch.utils.isBlank(r)) {
				return null;
			}

			var dto = JSON.parse(r);

			if (!!dto.error || !!dto.errors) {
				return dto;
			}

			if ($.isArray(dto)) {
				var connections = [];

				$.each(dto, function(index, connection) {
					connections.push(self.contentConnection.fromDto(connection));
				});

				connections.sort(self.contentConnection.sort);

				return connections;
			}

			if ($.isPlainObject(dto)) {
				return self.contentConnection.fromDto(dto);
			}

			return null;
		},
		formatRequest: function(connection) {
			return JSON.stringify(self.contentConnection.toDto(connection));
		},
		fromDto: function(dto) {
			var connection = new launch.ContentConnection();

			connection.id = parseInt(dto.id);
			connection.accountId = parseInt(dto.account_id);
			connection.connectionId = parseInt(dto.connection_id);
			connection.name = dto.name;
			connection.active = (parseInt(dto.status) === 1) ? true : false;
			connection.connectionType = dto.connection_type;
			connection.connectionSettings = dto.settings;
			connection.created = new Date(dto.created_at);
			connection.updated = new Date(dto.updated_at);
			connection.connectionName = dto.connection_name;
			connection.provider = dto.connection_provider;

			return connection;
		},
		toDto: function(connection) {
			return{
				id: connection.id,
				account_id: connection.accountId,
				connection_id: connection.connectionId,
				name: connection.name,
				status: (connection.active === true) ? 1 : 0,
				settings: connection.connectionSettings,
				connection_type: connection.connectionType,
				connection_name: connection.connectionName,
				connection_provider: connection.provider
			};
		},
		sort: function(a, b) {
			if (!a && !b) {
				return 0;
			} else if (!a && !!b) {
				return 1;
			} else if (!!a && !b) {
				return -1;
			}

			if (a.name === b.name) {
				if (a.connectionType === b.connectionType) {
					if (a.id === b.id) {
						return 0;
					} else if (a.id < b.id) {
						return -1;
					} else {
						return 1;
					}
				} else if (a.connectionType < b.connectionType) {
					return -1;
				} else {
					return 1;
				}
			} else {
				if (a.name < b.name) {
					return -1;
				} else {
					return 1;
				}
			}
		}
	};

	self.seoConnection = {
		parseResponse: function (r, getHeaders) {
			if (launch.utils.isBlank(r)) {
				return null;
			}

			var dto = JSON.parse(r);

			if (!!dto.error || !!dto.errors) {
				return dto;
			}

			if ($.isArray(dto)) {
				var connections = [];

				$.each(dto, function (index, connection) {
					connections.push(self.seoConnection.fromDto(connection));
				});

				connections.sort(self.seoConnection.sort);

				return connections;
			}

			if ($.isPlainObject(dto)) {
				return self.seoConnection.fromDto(dto);
			}

			return null;
		},
		formatRequest: function (connection) {
			return JSON.stringify(self.seoConnection.toDto(connection));
		},
		fromDto: function (dto) {
			var connection = new launch.SeoConnection();

			connection.id = parseInt(dto.id);
			connection.accountId = parseInt(dto.account_id);
			connection.name = dto.name;
			connection.active = (parseInt(dto.status) === 1) ? true : false;
			connection.connectionType = dto.type;
			connection.connectionSettings = dto.settings;
			connection.created = new Date(dto.created_at);
			connection.updated = new Date(dto.updated_at);

			return connection;
		},
		toDto: function (connection) {
			return {
				id: connection.id,
				account_id: connection.accountId,
				name: connection.name,
				status: (connection.active === true) ? 1 : 0
			};
		},
		sort: function (a, b) {
			if (!a && !b) {
				return 0;
			} else if (!a && !!b) {
				return 1;
			} else if (!!a && !b) {
				return -1;
			}

			if (a.name === b.name) {
				if (a.connectionType === b.connectionType) {
					if (a.id === b.id) {
						return 0;
					} else if (a.id < b.id) {
						return -1;
					} else {
						return 1;
					}
				} else if (a.connectionType < b.connectionType) {
					return -1;
				} else {
					return 1;
				}
			} else {
				if (a.name < b.name) {
					return -1;
				} else {
					return 1;
				}
			}
		}
	};

	self.content = {
		parseResponse: function(r, getHeaders) {
			if (launch.utils.isBlank(r)) {
				return null;
			}

			var dto = JSON.parse(r);

			if (!!dto.error || !!dto.errors) {
				return dto;
			}

			if ($.isArray(dto)) {
				var contents = [];

				$.each(dto, function(index, content) {
					contents.push(self.content.fromDto(content));
				});

				return contents;
			}

			if ($.isPlainObject(dto)) {
				return self.content.fromDto(dto);
			}

			return null;
		},
		formatRequest: function(content) {
			return JSON.stringify(self.content.toDto(content));
		},
		fromDto: function(dto) {
			if (launch.utils.isBlank(dto.id)) {
				return null;
			}

			var content = new launch.Content();

			content.id = parseInt(dto.id);
			content.accountId = parseInt(dto.account_id);

			content.title = dto.title;
			content.body = dto.body;

			if (!!dto.content_type) {
				content.contentType = self.contentType.fromDto(dto.content_type);
			} else {
				content.contentType = new launch.ContentType();
				content.contentType.id = parseInt(dto.content_type_id);
				content.contentType.name = dto.content_type_key;
				content.contentType.title = dto.content_type_name;
			}

			content.persona = dto.persona;
			content.secondaryPersona = dto.secondary_persona;
			content.buyingStage = dto.buying_stage;
			content.secondaryBuyingStage = dto.secondary_buying_stage;

			//content.connection = {
			//	id: parseInt(dto.connection_id),
			//	name: dto.connection_name
			//};

			content.campaign = {
				id: parseInt(dto.campaign_id),
				title: dto.campaign_title
			};

			if (!!dto.user_id) {
				content.author = new launch.User();
				content.author.id = parseInt(dto.user_id);
				content.author.userName = dto.user_username;
				content.author.image = dto.user_image;
			} else {
				content.author = self.user.fromDto(dto.user);
			}

			content.concept = dto.concept;
			content.status = parseInt(dto.status);
			content.archived = (parseInt(dto.archived) === 1);
			content.dueDate = launch.utils.isBlank(dto.due_date) ? null : new Date(dto.due_date);
			content.created = launch.utils.isBlank(dto.created_at) ? null : new Date(dto.created_at);
			content.updated = launch.utils.isBlank(dto.updated_at) ? null : new Date(dto.updated_at);

			content.collaborators = ($.isArray(dto.collaborators)) ? $.map(dto.collaborators, self.user.fromDto) : null;
			content.comments = ($.isArray(dto.comments)) ? $.map(dto.comments, self.comment.fromDto) : null;
			content.accountConnections = ($.isArray(dto.account_connections)) ? $.map(dto.account_connections, self.contentConnection.fromDto) : null;

			content.related = null;
			content.tags = null;

			// TODO: REMOVE THIS WHEN IT COMES FROM THE API!!
			if (isNaN(content.status)) { content.status = 1; }

			return content;
		},
		toDto: function(content) {
			var dto = {
				id: content.id,
				account_id: content.accountId,
				title: content.title,
				body: content.body,
				content_type: self.contentType.toDto(content.contentType),
				persona: content.persona,
				secondary_persona: content.secondaryPersona,
				buying_stage: content.buyingStage,
				secondardy_buying_stage: content.secondaryBuyingStage,
				user: self.user.toDto(content.author),
				concept: content.concept,
				status: content.status,
				archived: (content.archived === true) ? 1 : 0,
				created_at: content.created,
				updated_at: content.updated
			};

			if (!!content.campaign) {
				dto.campaign_id = content.campaign.id;
				dto.campaign_title = content.campaign.title;
			}

			dto.collaborators = $.isArray(content.collaborators) ? $.map(content.collaborators, self.user.toDto) : null;
			dto.comments = $.isArray(content.comments) ? $.map(content.comments, self.comment.toDto) : null;
			dto.account_connections = $.isArray(content.accountConnections) ? $.map(content.accountConnections, self.contentConnection.toDto) : null;

			dto.related = null;
			dto.tags = null;

			return dto;
		}
	};

	self.concept = {
		parseResponse: function (r, getHeaders) {
			if (launch.utils.isBlank(r)) {
				return null;
			}

			var dto = JSON.parse(r);

			if (!!dto.error || !!dto.errors) {
				return dto;
			}

			if ($.isArray(dto)) {
				var concepts = [];

				$.each(dto, function (index, concept) {
					concepts.push(self.concept.fromDto(concept));
				});

				return concepts;
			}

			if ($.isPlainObject(dto)) {
				return self.concept.fromDto(dto);
			}

			return null;
		},
		formatRequest: function (concept) {
			return JSON.stringify(self.concept.toDto(concept));
		},
		fromDto: function (dto) {
			//if (launch.utils.isBlank(dto.id)) {
			//	return null;
			//}

			var concept = new launch.Concept();

			concept.id = parseInt(dto.id);
			concept.title = dto.title;
			concept.conceptType = dto.concept_type;
			concept.contentType = dto.contentType;
			concept.description = dto.description;
			concept.campaign = dto.campaign;
			concept.creator = {
				id: dto.author.id,
				name: dto.author.name,
				image: dto.author.image
			};
			concept.collaborators = dto.collaborators;
			concept.discussion = $.map(dto.content_comments, self.comment.fromDto);

			return concept;
		},
		toDto: function (concept) { }
	};

	self.contentType = {
		parseResponse: function(r, getHeaders) {
			if (launch.utils.isBlank(r)) {
				return null;
			}

			var dto = JSON.parse(r);

			if (!!dto.error || !!dto.errors) {
				return dto;
			}

			if ($.isArray(dto)) {
				var contentTypes = [];

				$.each(dto, function (index, contentType) {
					contentTypes.push(self.contentType.fromDto(contentType));
				});

				return contentTypes;
			}

			if ($.isPlainObject(dto)) {
				return self.contentType.fromDto(dto);
			}

			return null;
		},
		fromDto: function (dto) {
			if (!dto) {
				return null;
			}

			var contentType = new launch.ContentType();

			contentType.id = parseInt(dto.id);
			contentType.name = dto.key;
			contentType.title = dto.name;

			return contentType;
		},
		toDto: function(contentType) {
			return {
				id: contentType.id,
				key: contentType.name,
				name: contentType.title
			};
		}
	};

	self.comment = {
		fromDto: function(dto) {
			if (!dto) {
				return null;
			}

			var comment = new launch.Comment();

			comment.id = parseInt(dto.id);
			comment.contentId = parseInt(dto.content_id);
			comment.comment = dto.comment;
			comment.created = new Date(dto.created_at);
			comment.updated = new Date(dto.updated_at);

			comment.commentor = {
				id: parseInt(dto.user_id),
				name: dto.user_name,
				image: dto.user_image
			};

			return comment;
		},
		toDto: function(comment) {
			return {
				id: comment.id,
				content_id: comment.contentId,
				comment: comment.comment,
				user_id: comment.commentor.id,
				created_at: comment.created,
				updated_at: comment.updated
			};
		}
	};

	return self;
};

launch.module.factory('ModelMapperService', function ($location, AuthService, NotificationService) {
	return new launch.ModelMapper($location, AuthService, NotificationService);
});
