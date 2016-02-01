launch.ModelMapper = function($location, authService, notificationService) {
	var self = this;

	self.parseResponse = function(r, getHeaders, fromDto, sort) {
		if (launch.utils.isBlank(r) || !$.isFunction(fromDto)) {
			return null;
		}

		var dto = JSON.parse(r);

		if (!!dto.error || !!dto.errors) {
			return dto;
		}

		if ($.isArray(dto)) {
			var items = [];

			$.each(dto, function(index, item) {
				items.push(fromDto(item));
			});

			if ($.isFunction(sort)) {
				items.sort(sort);
			}

			return items;
		}

		if ($.isPlainObject(dto)) {
			return fromDto(dto);
		}

		return null;
	};

	self.auth = {
		parseResponse: function(r, getHeaders) {
			return self.parseResponse(r, getHeaders, self.auth.fromDto);
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
			auth.impersonating = user.impersonating;
			auth.preferences = user.preferences;

			auth.modules = $.map(dto.modules, function(m) {
				var module = self.module.fromDto(m);

				if (!!dto.permissions) {
					module.privileges = $.map($.grep(dto.permissions, function(p) {
						return p.module.toLowerCase() === module.name;
					}), function(p) {
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
			auth.phoneNumber = cachedAuth.phoneNumber;
			auth.confirmed = cachedAuth.confirmed;
			auth.active = cachedAuth.active;
			auth.image = cachedAuth.image;
			auth.account = cachedAuth.account;
			auth.role = cachedAuth.role;
			auth.created = cachedAuth.created;
			auth.updated = cachedAuth.updated;
			auth.impersonating = cachedAuth.impersonating;
			auth.preferences = cachedAuth.preferences;

			auth.modules = cachedAuth.modules;

			return auth;
		}
	};

	self.account = {
		parseResponse: function(r, getHeaders) {
			return self.parseResponse(r, getHeaders, self.account.fromDto, self.account.sort);
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
			account.name = dto.name;
			account.active = (parseInt(dto.active) === 1) ? true : false;
			account.address1 = dto.address;
			account.address2 = dto.address_2;
			account.city = dto.city;
			account.state = (!!state) ? state : { value: dto.state, name: null };
			account.postalCode = dto.zipcode;
			account.country = dto.country;
			account.email = dto.email;
			account.phoneNumber = dto.phone;
			account.strategy = dto.strategy;
			account.userCount = parseInt(dto.count_users);
			account.created = dto.created_at;
			account.updated = dto.updated_at;
			account.paymentDate = dto.payment_date;

			account.subscription = self.subscription.fromDto(dto.account_subscription);

			if ($.isArray(dto.modules) && dto.modules.length > 0) {

				account.subscription.components = $.map(dto.modules, function(m) {
					var module = self.module.fromDto(m);
					return module;
				});
			}

			account.autoRenew = parseInt(dto.auto_renew) === 1 ? true : false;
            account.expirationDate = launch.utils.isBlank(dto.expiration_date) ?
                null :
                new Date(moment(dto.expiration_date, 'YYYY-MM-DD HH:mm:ss').format());
			account.paymentType = dto.payment_type;
			account.yearlyPayment = parseInt(dto.yearly_payment) === 1 ? true : false;
			account.hasToken = dto.hasToken;
			account.tokenizedType = dto.payment_type;

			account.creditCard = new launch.CreditCard();
			account.bankAccount = new launch.BankAccount();
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
            console.log(account)
			var dto = {
				id: account.id,
				title: account.title,
				name: account.name,
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
				strategy: account.strategy,
				created_at: account.created,
				updated_at: account.updated,
				payment_date: account.paymentDate
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
			account.paymentDate = cachedAccount.paymentDate;

			account.subscription = self.subscription.fromCache(cachedAccount.subscription);

			account.autoRenew = cachedAccount.autoRenew;
			account.expirationDate = launch.utils.isBlank(cachedAccount.expirationDate) ?
                null :
                new Date(moment(cachedAccount.expirationDate, 'YYYY-MM-DD HH:mm:ss').format());
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

	self.accountBeta = {
		formatRequest: function (account) {
			return JSON.stringify(self.accountBeta.toDto(account));
		},
		toDto: function(account) {
			var dto = self.account.toDto(account);

			dto.subscription_id = account.subscription.id;
			dto.id = account.subscription.id;
			dto.licenses = account.subscription.numberLicenses;
			dto.monthly_price = account.subscription.pricePerMonth;
			dto.training = account.subscription.training;
			dto.annual_discount = account.subscription.annualDiscount;
			dto.features = account.subscription.features;
			dto.subscription_level = account.subscription.subscriptionLevel;
			dto.first_name = account.firstName;
			dto.last_name = account.lastName;

			return dto;
		}
	};

	self.user = {
		parseResponse: function(r, getHeaders) {
			return self.parseResponse(r, getHeaders, self.user.fromDto, self.user.sort);
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
			user.created = launch.utils.isBlank(dto.created_at) ?
                null :
                new Date(moment(dto.created_at, 'YYYY-MM-DD HH:mm:ss').format());
			user.updated = launch.utils.isBlank(dto.updated_at) ?
                null :
                new Date(moment(dto.updated_at, 'YYYY-MM-DD HH:mm:ss').format());
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
			user.preferences = dto.preferences;
			user.super = (parseInt(dto.super) === 1) ? true : false;

			if (dto.impersonating) {
				user.impersonating = true;
			}

			if (!!dto.image && isNaN(dto.image)) {
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
				roles: $.isArray(user.roles) ? $.map(user.roles, function(r, i) { return self.role.toDto(r); }) : null,
				super: (user.super === true) ? 1 : 0
			};

			if ((!$.isArray(dto.roles) || dto.roles.length === 0) && !!user.role) {
				dto.roles = [self.role.toDto(user.role)];
			}

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
			user.preferences = cachedUser.preferences;

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
			return self.parseResponse(r, getHeaders, self.role.fromDto, self.role.sort);
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
			role.created = launch.utils.isBlank(dto.created_at) ?
                null :
                new Date(moment(dto.created_at, 'YYYY-MM-DD HH:mm:ss').format());
			role.updated = launch.utils.isBlank(dto.updated_at) ?
                null :
                new Date(moment(dto.updated_at, 'YYYY-MM-DD HH:mm:ss').format());

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
						var execPrivilege = self.privilege.fromDto(p);
						execPrivs.push(execPrivilege);
					}
				});

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

				launchModule.name = 'promote';
				launchModule.active = false;
				launchModule.title = 'Promote';
				launchModule.created = null;
				launchModule.updated = null;
				launchModule.privileges = mergePrivs('promote');

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
			return self.parseResponse(r, getHeaders, self.subscription.fromDto, self.subscription.sort);
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
			subscription.created = launch.utils.isBlank(dto.created_at) ?
                null :
                new Date(moment(dto.created_at, 'YYYY-MM-DD HH:mm:ss').format());
			subscription.updated = launch.utils.isBlank(dto.updated_at) ?
                null :
                new Date(moment(dto.updated_at, 'YYYY-MM-DD HH:mm:ss').format());

			// TODO: SET THE COMPONENTS CORRECTLY FROM THE API WHEN IT'S READY!!
			//			THIS IS ALREADY DONE WHEN GETTING MODULES FOR AN ACCOUNT (EXCEPT FOR THE "active" FLAG).
			//			STILL NEEDS TO BE DONE WHEN GETTING COMPONENTS FOR A SUBSCRIPTION AND FOR A USER ROLE.
			subscription.components = [
				{ name: 'create', title: 'CREATE', active: true },
				{ name: 'calendar', title: 'CALENDAR', active: true },
				{ name: 'promote', title: 'PROMOTE', active: true },
				{ name: 'measure', title: 'MEASURE', active: true }
			];

			if (subscription.subscriptionLevel >= 2) {
				subscription.components.push({ name: 'collaborate', title: 'COLLABORATE', active: true });
			}

			if (subscription.subscriptionLevel >= 3) {
				subscription.components.push({ name: 'consult', title: 'CONSULT', active: true });
			}
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
			subscription.created = launch.utils.isBlank(cachedSubscription.created) ?
                null :
                new Date(moment(cachedSubscription.created, 'YYYY-MM-DD HH:mm:ss').format());
			subscription.updated = launch.utils.isBlank(cachedSubscription.updated) ?
                null :
                new Date(moment(cachedSubscription.updated, 'YYYY-MM-DD HH:mm:ss').format());
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
			module.active = parseInt(dto.active) === 1;
			module.name = dto.name;
			module.title = dto.title;
			module.isSubscribable = dto.subscribable == true;
            module.created = launch.utils.isBlank(dto.created_at) ?
                null :
                new Date(moment(dto.created_at, 'YYYY-MM-DD HH:mm:ss').format());
            module.updated = launch.utils.isBlank(dto.updated_at) ?
                null :
                new Date(moment(dto.updated_at, 'YYYY-MM-DD HH:mm:ss').format());

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
			module.created = launch.utils.isBlank(cachedModule.created_at) ?
                null :
                new Date(moment(cachedModule.created_at, 'YYYY-MM-DD HH:mm:ss').format());
			module.updated = launch.utils.isBlank(cachedModule.updated_at) ?
                null :
                new Date(moment(cachedModule.updated_at, 'YYYY-MM-DD HH:mm:ss').format());

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
			privilege.view = (accessType === 'view');
			privilege.edit = (accessType === 'edit');
			privilege.execute = (accessType === 'execute' && dto.access === 1);

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
				if (moduleA === 'promote') {
					return -1;
				}
				if (moduleB === 'promote') {
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
		parseResponse: function(r, getHeaders) {
			return self.parseResponse(r, getHeaders, self.contentSettings.fromDto);
		},
		formatRequest: function(settings) {
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

			if ($.isArray(dto.persona_columns)) {
				settings.personaProperties = $.map(dto.persona_columns, function (p) { return launch.utils.titleCase(p); });
				settings.personas = $.map(dto.personas, function (p) {
					return {
						name: p.name,
						properties: p.columns
					};
				});
			}

			settings.created = launch.utils.isBlank(dto.created_at) ?
                null :
                new Date(moment(dto.created_at, 'YYYY-MM-DD HH:mm:ss').format());
			settings.updated = launch.utils.isBlank(dto.updated_at) ?
                null :
                new Date(moment(dto.updated_at, 'YYYY-MM-DD HH:mm:ss').format());

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

	self.connectionProvider = {
		parseResponse: function (r, getHeaders) {
			return self.parseResponse(r, getHeaders, self.connectionProvider.fromDto, self.connectionProvider.sort);
		},
		fromDto: function (dto) {
			var provider = new launch.ConnectionProvider();

			provider.id = parseInt(dto.id);
			provider.name = dto.name;
			provider.provider = dto.provider;
			provider.connectionType = dto.type;
			provider.created = launch.utils.isBlank(dto.created_at) ?
                null :
                new Date(moment(dto.created_at, 'YYYY-MM-DD HH:mm:ss').format());
			provider.updated = launch.utils.isBlank(dto.updated_at) ?
                null :
                new Date(moment(dto.updated_at, 'YYYY-MM-DD HH:mm:ss').format());
			provider.category = dto.category;

			return provider;
		},
		sort: function(a, b) {
			if (!a && !b) { return 0; }
			if (!a && !!b) { return 1; }
			if (!!a && !b) { return -1; }

			if (a.name === b.name) {
				if (a.provider === b.provider) {
					if (a.id === b.id) { return 0; }
					if (a.id < b.id) { return -1; }
					
					return 1;
				}

				return (a.provider < b.provider) ? -1 : 1;
			} else {
				return (a.name < b.name) ? -1 : 1;
			}
		}
	};

	self.connection = {
		parseResponse: function (r, getHeaders) {
			return self.parseResponse(r, getHeaders, self.connection.fromDto, self.connection.sort);
		},
		formatRequest: function (connection) {
			return JSON.stringify(self.connection.toDto(connection));
		},
		fromDto: function (dto) {
			switch (dto.connection_provider) {
				case 'acton':
				case 'hubspot':
				case 'hootsuite':
				case 'outbrain':
				case 'papershare':
					return self.promoteConnection.fromDto(dto);
				case 'all-in-one':
				case 'scribe':
				case 'yoast':
					return self.seoConnection.fromDto(dto);
				default:
					return self.contentConnection.fromDto(dto);
			}
		},
		toDto: function(connection) {
			return {
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

	self.contentConnection = {
		fromDto: function (dto) {
			var connection = new launch.ContentConnection();

			connection.id = parseInt(dto.id);
			connection.accountId = parseInt(dto.account_id);
			connection.connectionId = parseInt(dto.connection_id);
			connection.active = (parseInt(dto.status) === 1) ? true : false;
			connection.connectionType = 'content';
			connection.connectionSettings = dto.settings;
			connection.created = launch.utils.isBlank(dto.created_at) ?
                null :
                new Date(moment(dto.created_at, 'YYYY-MM-DD HH:mm:ss').format());
			connection.updated = launch.utils.isBlank(dto.updated_at) ?
                null :
                new Date(moment(dto.updated_at, 'YYYY-MM-DD HH:mm:ss').format());
			connection.connectionName = dto.connection_name;
			connection.provider = dto.connection_provider;
			connection.identifier = dto.identifier;
			connection.url = dto.url;

			if (launch.utils.isBlank(dto.connection_provider) || launch.utils.isBlank(dto.name) ||
				dto.connection_provider.toLowerCase() === dto.name.toLowerCase() || 
				(dto.connection_provider === 'google-drive' && dto.name.toLowerCase() === 'google drive') || 
				(dto.connection_provider === 'google-plus' && dto.name.toLowerCase() === 'google+') ) {
				connection.name = launch.utils.isBlank(dto.identifier) ? dto.name : dto.identifier;
			} else {
				connection.name = dto.name;
			}

			if (launch.utils.isBlank(connection.provider) && $.isPlainObject(dto.connection)) {
				connection.provider = dto.connection.provider;
				connection.connectionType = dto.connection.type;
				connection.connectionName = dto.connection.name;
				connection.connectionCategory = dto.connection.category;
			}

			return connection;
		},
		toDto: function (connection) {
			return {
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
		}
	};

	self.promoteConnection = {
		fromDto: function (dto) {
			var connection = new launch.PromoteConnection();

			connection.id = parseInt(dto.id);
			connection.accountId = parseInt(dto.account_id);
			connection.connectionId = parseInt(dto.connection_id);
			connection.name = dto.name;
			connection.active = (parseInt(dto.status) === 1) ? true : false;
			connection.connectionType = 'promote';
			connection.connectionSettings = dto.settings;
			connection.created = launch.utils.isBlank(dto.created_at) ?
                null :
                new Date(moment(dto.created_at, 'YYYY-MM-DD HH:mm:ss').format());
			connection.updated = launch.utils.isBlank(dto.updated_at) ?
                null :
                new Date(moment(dto.updated_at, 'YYYY-MM-DD HH:mm:ss').format());
			connection.connectionName = dto.connection_name;
			connection.provider = dto.connection_provider;
			connection.url = dto.url;

			if (launch.utils.isBlank(dto.connection_provider) || launch.utils.isBlank(dto.name) ||
				dto.connection_provider.toLowerCase() === dto.name.toLowerCase() || 
				(dto.connection_provider === 'acton' && dto.name.toLowerCase() === 'act-on') ) {
				connection.name = launch.utils.isBlank(dto.identifier) ? dto.name : dto.identifier;
			} else {
				connection.name = dto.name;
			}

			if (launch.utils.isBlank(connection.provider) && $.isPlainObject(dto.connection)) {
				connection.provider = dto.connection.provider;
				connection.connectionType = dto.connection.type;
				connection.connectionName = dto.connection.name;
				connection.connectionCategory = dto.connection.category;
			}

			return connection;
		},
		toDto: function (connection) {
			return {
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
		}
	};

	self.seoConnection = {
		fromDto: function(dto) {
			var connection = new launch.SeoConnection();

			connection.id = parseInt(dto.id);
			connection.accountId = parseInt(dto.account_id);
			connection.name = dto.name;
			connection.active = (parseInt(dto.status) === 1) ? true : false;
			connection.connectionType = 'seo';
			connection.connectionCategory = dto.category;
			connection.connectionSettings = dto.settings;
			connection.created = launch.utils.isBlank(dto.created_at) ?
                null :
                new Date(moment(dto.created_at, 'YYYY-MM-DD HH:mm:ss').format());
			connection.updated = launch.utils.isBlank(dto.updated_at) ?
                null :
                new Date(moment(dto.updated_at, 'YYYY-MM-DD HH:mm:ss').format());

			return connection;
		},
		toDto: function(connection) {
			return {
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
		}
	};

	self.content = {
		parseResponse: function(r, getHeaders) {
			return self.parseResponse(r, getHeaders, self.content.fromDto);
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

			if (!!dto.upload) {
				content.contentFile = self.uploadFile.fromDto(dto.upload);
			}

			if (!!dto.uploads) {
				content.attachments = $.map(dto.uploads, self.uploadFile.fromDto);
			}

			content.persona = dto.persona;
			content.secondaryPersona = dto.secondary_persona;
			content.buyingStage = dto.buying_stage;
			content.secondaryBuyingStage = dto.secondary_buying_stage;

			if (!!dto.campaign) {
				content.campaign = self.campaign.fromDto(dto.campaign);
			} else {
				content.campaign = new launch.Campaign();
				content.campaign.id = parseInt(dto.campaign_id);
				content.campaign.title = dto.campaign_title;
			}

			if (!!dto.user) {
				content.author = self.user.fromDto(dto.user);
			} else {
				content.author = new launch.User();
				content.author.id = parseInt(dto.user_id);
				content.author.userName = dto.user_username;
				content.author.image = dto.user_image;
			}

			if ($.isArray(dto.activities)) {
				content.activity = $.map(dto.activities, self.contentActivity.fromDto);
			}

			content.concept = dto.concept;
			content.status = parseInt(dto.status);
			content.archived = (parseInt(dto.archived) === 1);
			content.dueDate = launch.utils.isBlank(dto.due_date) ?
                null :
                new Date(moment(dto.due_date, 'YYYY-MM-DD HH:mm:ss').format());
			content.convertDate = launch.utils.isBlank(dto.convert_date) ?
                null :
                new Date(moment(dto.convert_date, 'YYYY-MM-DD HH:mm:ss').format());
			content.submitDate = launch.utils.isBlank(dto.submit_date) ?
                null :
                new Date(moment(dto.submit_date, 'YYYY-MM-DD HH:mm:ss').format());
			content.approveDate = launch.utils.isBlank(dto.approve_date) ?
                null :
                new Date(moment(dto.approve_date, 'YYYY-MM-DD HH:mm:ss').format());
			content.launchDate = launch.utils.isBlank(dto.launch_date) ?
                null :
                new Date(moment(dto.launch_date, 'YYYY-MM-DD HH:mm:ss').format());
			content.promoteDate = launch.utils.isBlank(dto.promote_date) ?
                null :
                new Date(moment(dto.promote_date, 'YYYY-MM-DD HH:mm:ss').format());
			content.archiveDate = launch.utils.isBlank(dto.archive_date) ?
                null :
                new Date(moment(dto.archive_date, 'YYYY-MM-DD HH:mm:ss').format());
			content.created = launch.utils.isBlank(dto.created_at) ?
                null :
                new Date(moment(dto.created_at, 'YYYY-MM-DD HH:mm:ss').format());
			content.updated = launch.utils.isBlank(dto.updated_at) ?
                null :
                new Date(moment(dto.updated_at, 'YYYY-MM-DD HH:mm:ss').format());

			content.collaborators = ($.isArray(dto.collaborators)) ? $.map(dto.collaborators, self.user.fromDto) : null;
			content.comments = ($.isArray(dto.comments)) ? $.map(dto.comments, self.comment.fromDto) : null;
			content.accountConnections = ($.isArray(dto.account_connections)) ? $.map(dto.account_connections, self.connection.fromDto) : null;

            if ($.isArray(dto.related)) {
                content.relatedContent = $.map(dto.related, function(r, i) { return r.related_content; });
            }

			if ($.isArray(dto.tags)) {
				content.tags = $.map(dto.tags, function(t, i) { return t.tag; });
			}

			content.metaDescription = dto.meta_description;
			content.metaKeywords = dto.meta_keywords;

			if (content.contentType.name === 'product-description') {
				content.ecommercePlatform = dto.ecommerce_platform;
			}

			if ($.isArray(dto.task_groups)) {
				content.taskGroups = $.map(dto.task_groups, self.taskGroups.fromDto);
			}

			if (!!dto.automation) {
				content.automation = dto.automation;
			}

            if(dto.scores && dto.scores.length) {
                content.contentScore = parseInt(dto.scores[0].score);
            }
            else {
                content.contentScore = 0;
            }

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
				secondary_buying_stage: content.secondaryBuyingStage,
				user: self.user.toDto(content.author),
				concept: content.concept,
				status: content.status,
				archived: (content.archived === true) ? 1 : 0,
				meta_description: content.metaDescription,
				meta_keywords: content.metaKeywords,
				convert_date: content.convertDate,
				submit_date: content.submitDate,
				approve_date: content.approveDate,
				launch_date: content.launchDate,
				promote_date: content.promoteDate,
				archive_date: content.archiveDate,
				ecommerce_platform: (content.contentType.name === 'product-description' ? content.ecommercePlatform : null),
				created_at: content.created,
				updated_at: content.updated
			};

			if (!!content.contentFile && !launch.utils.isBlank(content.contentFile.id)) {
				dto.upload = self.uploadFile.toDto(content.contentFile);
			} else {
				dto.upload = null;
			}

			if ($.isArray(content.attachments) && content.attachments.length > 0) {
				dto.uploads = $.map(content.attachments, self.uploadFile.toDto);
			} else {
				dto.uploads = null;
			}

			if (!!content.campaign) {
				dto.campaign = {
					id: content.campaign.id,
					title: content.campaign.title
				};
			}

			dto.collaborators = $.isArray(content.collaborators) ? $.map(content.collaborators, self.user.toDto) : null;
			dto.comments = $.isArray(content.comments) ? $.map(content.comments, self.comment.toDto) : null;
			dto.account_connections = $.isArray(content.accountConnections) ? $.map(content.accountConnections, self.connection.toDto) : null;

			dto.related = $.isArray(content.relatedContent) ? $.map(content.relatedContent, function (t) { return { related_content : t }; }) : null;
			dto.tags = $.isArray(content.tags) ? $.map(content.tags, function (t) { return { tag: t }; }) : null;

			return dto;
		}
	};

	self.contentActivity = {
		parseResponse: function(r, getHeaders) {
			return self.parseResponse(r, getHeaders, self.contentActivity.fromDto);
		},
		fromDto: function(dto) {
			var activity = new launch.ContentActivity();

			activity.id = parseInt(dto.id);
			activity.contentId = parseInt(dto.content_id);
			activity.userId = parseInt(dto.user_id);
			activity.title = dto.activity;
			activity.created = launch.utils.isBlank(dto.created_at) ?
                null :
                new Date(moment(dto.created_at, 'YYYY-MM-DD HH:mm:ss').format());
			activity.updated = launch.utils.isBlank(dto.updated_at) ?
                null :
                new Date(moment(dto.updated_at, 'YYYY-MM-DD HH:mm:ss').format());

			return activity;
		}
	};

	self.contentType = {
		parseResponse: function(r, getHeaders) {
			return self.parseResponse(r, getHeaders, self.contentType.fromDto, self.contentType.sort);
		},
		fromDto: function(dto) {
			if (!dto) {
				return null;
			}

			var contentType = new launch.ContentType();

			contentType.id = parseInt(dto.id);
			contentType.name = dto.key;
			contentType.title = dto.name;
			contentType.baseType = dto.base_type;
			contentType.isVisible = (parseInt(dto.visible) === 1);

			return contentType;
		},
		fromCache: function(cachedContentType) {
			var contentType = new launch.ContentType();

			contentType.id = cachedContentType.id;
			contentType.name = cachedContentType.name;
			contentType.title = cachedContentType.title;
			contentType.baseType = cachedContentType.baseType;
			contentType.isVisible = cachedContentType.isVisible;

			return contentType;
		},
		toDto: function(contentType) {
			return {
				id: contentType.id,
				key: contentType.name,
				name: contentType.title
			};
		},
		sort: function(a, b) {
			if ((!a && !b) || (launch.utils.isBlank(a.title) && launch.utils.isBlank(b.title))) { return 0; }
			if ((!a && !!b) || (launch.utils.isBlank(a.title) && !launch.utils.isBlank(b.title))) { return 1; }
			if ((!!a && !b) || (!launch.utils.isBlank(a.title) && launch.utils.isBlank(b.title))) { return -1; }

			if (a.title < b.title) { return -1; }
			if (a.title > b.title) { return 1; }

			return 0;
		}
	};

	self.comment = {
		parseResponse: function(r, getHeaders) {
			return self.parseResponse(r, getHeaders, self.comment.fromDto);
		},
		formatRequest: function(comment) {
			return JSON.stringify(self.comment.toDto(comment));
		},
		fromDto: function(dto) {
			if (!dto) {
				return null;
			}

			var comment = new launch.Comment();

			comment.id = parseInt(dto.id);
			comment.itemId = parseInt(dto.content_id);
			comment.comment = dto.comment;
			comment.created = launch.utils.isBlank(dto.created_at) ?
                null :
                new Date(moment(dto.created_at, 'YYYY-MM-DD HH:mm:ss').format());
			comment.updated = launch.utils.isBlank(dto.updated_at) ?
                null :
                new Date(moment(dto.updated_at, 'YYYY-MM-DD HH:mm:ss').format());

			if (!!dto.guest) {
				comment.commentor = self.guestCollaborator.fromDto(dto.guest);
				comment.isGuestComment = true;
			} else {
				comment.isGuestComment = false;
				comment.commentor = self.user.fromDto(dto.user);
			}


			return comment;
		},
		toDto: function (comment) {
			var dto = {
				id: comment.id,
				content_id: comment.itemId,
				comment: comment.comment,
				created_at: comment.created,
				updated_at: comment.updated
			};

			if (comment.isGuestComment) {
				dto.guest_id = comment.commentor.id;
				dto.user_id = null;
			} else {
				dto.user_id = comment.commentor.id;
				dto.guest_id = null;
			}

			return dto;
		}
	};

	self.campaign = {
		parseResponse: function(r, getHeaders) {
			return self.parseResponse(r, getHeaders, self.campaign.fromDto);
		},
		formatRequest: function(campaign) {
			return JSON.stringify(self.campaign.toDto(campaign));
		},
		fromDto: function(dto) {
			var campaign = new launch.Campaign();

			campaign.id = parseInt(dto.id);
			campaign.accountId = parseInt(dto.account_id);
			campaign.user = self.user.fromDto(dto.user);
			campaign.isActive = (parseInt(dto.is_active) === 1) ? true : false;

			campaign.title = dto.title;
			campaign.description = dto.description;
			campaign.concept = dto.concept;
			campaign.campaignType = dto.campaign_type;
			campaign.type = dto.type;
			campaign.status = parseInt(dto.status);
			campaign.goals = dto.goals;
			campaign.color = dto.color;

			campaign.startDate = launch.utils.isBlank(dto.start_date) ?
                null :
                new Date(moment(dto.start_date, 'YYYY-MM-DD HH:mm:ss').format());
			campaign.endDate = launch.utils.isBlank(dto.end_date) ?
                null :
                new Date(moment(dto.end_date, 'YYYY-MM-DD HH:mm:ss').format());
			campaign.isRecurring = (parseInt(dto.is_recurring) === 1) ? true : false;
			campaign.isSeries = (parseInt(dto.is_series) === 1) ? true : false;
			campaign.recurringId = parseInt(dto.recurring_id);

			campaign.contact = dto.contact;
			campaign.host = dto.host;
			campaign.speakerName = dto.speaker_name;
			campaign.partners = dto.partners;
			campaign.audioLink = dto.audio_link;

			campaign.linkNeeded = (parseInt(dto.link_needed) === 1) ? true : false;
			campaign.photoNeeded = (parseInt(dto.photo_needed) === 1) ? true : false;

			campaign.comments = ($.isArray(dto.comments)) ? $.map(dto.comments, self.comment.fromDto) : null;
			campaign.collaborators = ($.isArray(dto.collaborators)) ? $.map(dto.collaborators, self.user.fromDto) : null;
			campaign.guestCollaborators = ($.isArray(dto.guest_collaborators)) ? $.map(dto.guest_collaborators, self.guestCollaborator.fromDto) : null;

			if ($.isArray(dto.tags)) {
				campaign.tags = $.map(dto.tags, function(t, i) {
					return t.tag;
				});
			}

			campaign.created = launch.utils.isBlank(dto.created_at) ?
                null :
                new Date(moment(dto.created_at, 'YYYY-MM-DD HH:mm:ss').format());
			campaign.updated = launch.utils.isBlank(dto.updated_at) ?
                null :
                new Date(moment(dto.updated_at, 'YYYY-MM-DD HH:mm:ss').format());

			return campaign;
		},
		toDto: function(campaign) {
			var dto = {
				id: campaign.id,
				account_id: campaign.accountId,
				user: self.user.toDto(campaign.user),
				user_id: campaign.user.id,
				is_active: campaign.isActive ? 1 : 0,
				title: campaign.title,
				description: campaign.description,
				concept: campaign.concept,
				campaign_type: campaign.campaignType,
				type: campaign.type,
				status: campaign.status,
				goals: campaign.goals,
				color: campaign.color,
				start_date: campaign.startDate,
				end_date: campaign.endDate,
				is_recurring: (campaign.isRecurring) ? 1 : 0,
				is_series: (campaign.isSeries) ? 1 : 0,
				recurring_id: campaign.recurringId,
				contact: campaign.contact,
				host: campaign.host,
				speaker_name: campaign.speakerName,
				partners: campaign.partners,
				audio_link: campaign.audioLink,
				link_needed: (campaign.linkNeeded) ? 1 : 0,
				photo_needed: (campaign.photoNeeded) ? 1 : 0,
				created_at: campaign.created,
				updated_at: campaign.updated
			};

			dto.collaborators = $.isArray(campaign.collaborators) ? $.map(campaign.collaborators, self.user.toDto) : null;
			dto.comments = $.isArray(campaign.comments) ? $.map(campaign.comments, self.comment.toDto) : null;
			dto.guest_collaborators = $.isArray(campaign.guestCollaborators) ? $.map(campaign.guestCollaborators, self.guestCollaborator.toDto) : null;
			dto.tags = $.isArray(campaign.tags) ? $.map(campaign.tags, function(t) { return { tag: t }; }) : null;

			return dto;
		}
	};

	self.taskGroups = {
		parseResponse: function(r, getHeaders) {
			return self.parseResponse(r, getHeaders, self.taskGroups.fromDto);
		},
		formatRequest: function(taskGroup) {
			return JSON.stringify(self.taskGroups.toDto(taskGroup));
		},
		fromDto: function(dto) {
			var taskGroup = new launch.TaskGroup();

			taskGroup.id = parseInt(dto.id);
			taskGroup.contentId = parseInt(dto.content_id);
			taskGroup.status = parseInt(dto.status);
			taskGroup.isComplete = (parseInt(dto.is_complete) === 1) ? true : false;
			taskGroup.dueDate = launch.utils.isBlank(dto.due_date) ?
                null :
                new Date(moment(dto.due_date, 'YYYY-MM-DD HH:mm:ss').format());
			taskGroup.completeDate = launch.utils.isBlank(dto.date_completed) ?
                null :
                new Date(moment(dto.date_completed, 'YYYY-MM-DD HH:mm:ss').format());
			taskGroup.tasks = $.isArray(dto.tasks) ? $.map(dto.tasks, self.task.fromDto) : null;
			taskGroup.created = launch.utils.isBlank(dto.created_at) ?
                null :
                new Date(moment(dto.created_at, 'YYYY-MM-DD HH:mm:ss').format());
			taskGroup.updated = launch.utils.isBlank(dto.updated_at) ?
                null :
                new Date(moment(dto.updated_at, 'YYYY-MM-DD HH:mm:ss').format());

			return taskGroup;
		},
		toDto: function(taskGroup) {
			return {
				id: taskGroup.id,
				content_id: taskGroup.contentId,
				status: taskGroup.status,
				is_complete: (taskGroup.isComplete) ? 1 : 0,
				date_completed: taskGroup.completeDate,
				due_date: taskGroup.dueDate,
				tasks: $.isArray(taskGroup.tasks) ? $.map(taskGroup.tasks, self.task.toDto) : null,
				created_at: taskGroup.created,
				updated_at: taskGroup.updated
			};
		}
	};

	self.task = {
		fromDto: function(dto) {
			var task = new launch.Task();

			task.id = parseInt(dto.id);
			task.name = dto.name;
			task.isComplete = (parseInt(dto.is_complete) === 1) ? true : false;
			task.completeDate = launch.utils.isBlank(dto.date_completed) ?
                null :
                new Date(moment(dto.date_completed, 'YYYY-MM-DD HH:mm:ss').format());
			task.dueDate = launch.utils.isBlank(dto.due_date) ?
                null :
                new Date(moment(dto.due_date, 'YYYY-MM-DD HH:mm:ss').format());
			task.userId = parseInt(dto.user_id);
			task.taskGroupId = parseInt(dto.content_task_group_id);
			task.created = launch.utils.isBlank(dto.created_at) ?
                null :
                new Date(moment(dto.created_at, 'YYYY-MM-DD HH:mm:ss').format());
			task.updated = launch.utils.isBlank(dto.updated_at) ?
                null :
                new Date(moment(dto.updated_at, 'YYYY-MM-DD HH:mm:ss').format());

			return task;
		},
		toDto: function(task) {
			return {
				id: task.id,
				name: task.name,
				is_complete: (task.isComplete) ? 1 : 0,
				date_completed: task.completeDate,
				due_date: task.dueDate,
				user_id: task.userId,
				content_task_group_id: task.taskGroupId,
				created_at: task.created,
				updated_at: task.updated
			};
		}
	};

	self.uploadFile = {
		parseResponse: function(r, getHeaders) {
			return self.parseResponse(r, getHeaders, self.uploadFile.fromDto);
		},
		fromDto: function(dto) {
			var uploadFile = new launch.UploadFile();

			uploadFile.id = parseInt(dto.id);
			uploadFile.accountId = parseInt(dto.account_id);
			uploadFile.userId = isNaN(dto.user_id) ? null : parseInt(dto.user_id);
			uploadFile.parentId = isNaN(dto.parent_id) ? null : parseInt(dto.parent_id);
			uploadFile.description = dto.description;
			uploadFile.extension = dto.extension;
			uploadFile.fileName = dto.filename;
			uploadFile.mimeType = dto.mimetype;
			uploadFile.path = dto.path;
			uploadFile.size = parseInt(dto.size);
			uploadFile.created = launch.utils.isBlank(dto.created_at) ?
                null :
                new Date(moment(dto.created_at, 'YYYY-MM-DD HH:mm:ss').format());
			uploadFile.updated = launch.utils.isBlank(dto.updated_at) ?
                null :
                new Date(moment(dto.updated_at, 'YYYY-MM-DD HH:mm:ss').format());
			uploadFile.deleted = launch.utils.isBlank(dto.deleted_at) ?
                null :
                new Date(moment(dto.deleted_at, 'YYYY-MM-DD HH:mm:ss').format());

			var path = dto.path;

			if (launch.utils.startsWith(path, '/public')) {
				path = path.substring(7);
			}

			if (dto.tags) {
				uploadFile.tags = dto.tags;
			}

			if (dto.libraries) {
				uploadFile.libraries = dto.libraries;
			}

			if (dto.ratings) {
				uploadFile.ratings = dto.ratings;
			}

			if (dto.views) {
				uploadFile.views = dto.views;
			}

			uploadFile.path = path + '' + uploadFile.fileName;

			return uploadFile;
		},
		toDto: function(uploadFile) {
			return {
				id: uploadFile.id,
				account_id: uploadFile.accountId,
				user_id: uploadFile.userId,
				parent_id: uploadFile.parentId,
				extension: uploadFile.extension,
				filename: uploadFile.fileName,
				mimetype: uploadFile.mimeType,
				path: uploadFile.path,
				size: uploadFile.size,
				created_at: uploadFile.created,
				updated_at: uploadFile.updated,
				deleted_at: uploadFile.deleted
			};
		}
	};

	self.guestCollaborator = {
		parseResponse: function (r, getHeaders) {
			return self.parseResponse(r, getHeaders, self.guestCollaborator.fromDto);
		},
		fromDto: function (dto) {
			var guestCollaborator = new launch.GuestCollaborator();

			guestCollaborator.id = parseInt(dto.id);
			guestCollaborator.accountId = parseInt(dto.account_id);
			guestCollaborator.accepted = parseInt(dto.accepted === 1);
			guestCollaborator.accessCode = dto.access_code;
			guestCollaborator.connection = dto.connection;
			guestCollaborator.connetionUserId = dto.connection_user_id;
			guestCollaborator.name = dto.name;
			guestCollaborator.connectionType = dto.type;
			guestCollaborator.connetionSettings = dto.settings;
			guestCollaborator.created = launch.utils.isBlank(dto.created_at) ?
                null :
                new Date(moment(dto.created_at, 'YYYY-MM-DD HH:mm:ss').format());
			guestCollaborator.updated = launch.utils.isBlank(dto.updated_at) ?
                null :
                new Date(moment(dto.updated_at, 'YYYY-MM-DD HH:mm:ss').format());

			if ($.isArray(dto.content)) {
				guestCollaborator.content = $.map(dto.content, function (c) {
					return self.content.fromDto(c);
				});
			}

			if ($.isArray(dto.campaigns)) {
				guestCollaborator.campaigns = $.map(dto.campaigns, function (c) {
					return self.campaign.fromDto(c);
				});
			}


			return guestCollaborator;
		}
	};

	self.brainstorm = {
        parseResponse: function(r, getHeaders) {
            return self.parseResponse(r, getHeaders, self.brainstorm.fromDto);
        },
        formatRequest: function(brainstorm) {
        	return JSON.stringify(self.brainstorm.toDto(brainstorm));
        },
        fromDto: function(dto) {
            var brainstorm = new launch.Brainstorm();

            brainstorm.id = parseInt(dto.id);
            brainstorm.userId = dto.user_id;
            brainstorm.contentId = dto.content_id;
            brainstorm.campaignId = dto.campaign_id;
            brainstorm.accountId = dto.account_id;
            brainstorm.agenda = dto.agenda;

            brainstorm.datetime = launch.utils.isBlank(dto.datetime) ?
                null :
                new Date(moment.utc(dto.datetime, 'YYYY-MM-DD HH:mm:ss').format());
            brainstorm.date = moment(brainstorm.datetime).format('MM-DD-YYYY');
            brainstorm.time = brainstorm.datetime.getTime();

            brainstorm.description = dto.description;
            brainstorm.credentials = dto.credentials;
            brainstorm.contentType = brainstorm.contentId ? 'content' : 'campaign';
            brainstorm.created = launch.utils.isBlank(dto.created_at) ?
                null :
                new Date(moment(dto.created_at, 'YYYY-MM-DD HH:mm:ss').format());
            brainstorm.updated = launch.utils.isBlank(dto.updated_at) ?
                null :
                new Date(moment(dto.updated_at, 'YYYY-MM-DD HH:mm:ss').format());

            return brainstorm;
        },
        toDto: function (brainstorm) {
        	if (!brainstorm) {
		        return null;
	        }

	        return {
		        id: brainstorm.id,
		        user_id: brainstorm.userId,
		        content_id: brainstorm.contentId,
		        campaign_id: brainstorm.campaignId,
		        account_id: brainstorm.accountId,
		        agenda: brainstorm.agenda,
		        description: brainstorm.description,
		        credentials: brainstorm.credentials,
		        content_type: brainstorm.contentType,
		        datetime: launch.utils.isBlank(brainstorm.datetime) ?
                    null :
                    new Date(moment(brainstorm.datetime, 'YYYY-MM-DD HH:mm:ss').format())
	        };
        }
	};

	self.launchedContent = {
		parseResponse: function (r, getHeaders) {
			return self.parseResponse(r, getHeaders, self.launchedContent.fromDto);
		},
		fromDto: function(dto) {
			var lc = new launch.LaunchedContent();

			lc.id = parseInt(dto.id);
			lc.connection = self.contentConnection.fromDto(dto.account_connection);
			lc.accountConnectionId = parseInt(dto.account_connection_id);
			lc.contentId = parseInt(dto.content_id);
			lc.userId = parseInt(dto.user_id);
			lc.success = parseInt(dto.success) === 1;
			lc.response = dto.response;
			lc.created = launch.utils.isBlank(lc.created_at) ?
                null :
                new Date(moment(lc.created_at, 'YYYY-MM-DD HH:mm:ss').format());
			lc.updated = launch.utils.isBlank(lc.updated_at) ?
                null :
                new Date(moment(lc.updated_at, 'YYYY-MM-DD HH:mm:ss').format());
			lc.permalink = dto.permalink;

			return lc;
		}
	};

    self.measure = {
        parseResponse: function (r, getHeaders) {
            return self.parseResponse(r, getHeaders, self.measure.fromDto);
        },
        fromDto : function(dto) {
            console.log(dto);
            return dto
            debugger;
            var series = ($.isArray(dto)) ? $.map(dto, function(r, i) { return r; }) : [];
            console.log(series);
            return series;
        }
    }

	return self;
};

launch.module.factory('ModelMapperService', function($location, AuthService, NotificationService) {
	return new launch.ModelMapper($location, AuthService, NotificationService);
});
