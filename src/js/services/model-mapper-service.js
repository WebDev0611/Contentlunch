launch.ModelMapper = function (authService, notificationService) {
	var self = this;

	self.account = {
		parseResponse: function (r, getHeaders) {
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
		fromDto: function (dto) {
			var account = new launch.Account();

			account.id = parseInt(dto.id);
			account.title = account.name = dto.title;
			account.active = (parseInt(dto.active) === 1) ? 'active' : 'inactive';
			account.address1 = dto.address;
			account.address2 = dto.address_2;
			account.city = dto.city;
			account.state = { value: dto.state, name: null };
			account.postalCode = dto.zipcode;
			account.country = dto.country;
			account.email = dto.email;
			account.phoneNumber = dto.phone;
			account.autoRenew = (parseInt(dto.subscription) === 1);
			account.created = dto.created_at;
			account.updated = dto.updated_at;

			// TODO: THESE NEED TO BE ADDED TO THE DB AND API. WILL NEED TO CHANGE toDto METHOD AS WELL!
			account.numberOfUsers = 10;
			account.accountExpirationDate = new Date();

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
				active: (account.active === 'active') ? 1 : 0,
				address: account.address1,
				address_2: account.address2,
				city: account.city,
				state: (!!account.state) ? account.state.value : null,
				zipcode: account.postalCode,
				country: account.country,
				email: account.email,
				phone: account.phoneNumber,
				subscription: account.autoRenew ? 1 : 0,
				created_at: account.created,
				updated_at: account.updated
			};

			return JSON.stringify(dto);
		},
		fromCache: function (cachedAccount) {
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
			account.numberOfUsers = cachedAccount.numberOfUsers;
			account.accountExpirationDate = cachedAccount.accountExpirationDate;
			account.autoRenew = cachedAccount.autoRenew;
			account.created = cachedAccount.created;
			account.updated = cachedAccount.updated;

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
		fromDto: function(dto) {
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
			user.active = (parseInt(dto.status) === 1) ? 'active' : 'inactive';
			user.accounts = ($.isArray(dto.accounts)) ? $.map(dto.accounts, function(a, i) { return self.account.fromDto(a); }) : [];
			user.account = (user.accounts.length > 0) ? user.accounts[0] : null;
			user.roles = ($.isArray(dto.roles)) ? $.map(dto.roles, function (r, i) { return new launch.Role(r.id, r.name); }) : [];
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
				status: (user.active === 'active') ? 1 : 0,
				roles: [{ id: user.role.roleId, name: user.role.roleName }]
			};

			if (!launch.utils.isBlank(user.password) && !launch.utils.isBlank(user.passwordConfirmation)) {
				dto.password = user.password;
				dto.password_confirmation = user.passwordConfirmation;
			}

			return JSON.stringify(dto);
		},
		fromCache: function(cachedUser) {
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
			user.role = new launch.Role(cachedUser.role.roleId, cachedUser.role.roleName);
			user.roles = $.map(cachedUser.roles, function(r, i) { return new launch.Role(r.roleId, r.roleName); });
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
		fromDto: function(dto) {
			var role = new launch.Role(dto.id, dto.name);

			role.created = dto.created_at;
			role.updated = dto.updated_at;

			return role;
		},
		toDto: function(role) {
			return {
				id: role.roleId,
				name: roleName,
				created_at: role.created,
				updated_at: role.updated
			};
		},
		sort: function(a, b) {
			var roleA = launch.utils.isBlank(a.roleName) ? '' : a.roleName.toUpperCase();
			var roleB = launch.utils.isBlank(b.roleName) ? '' : b.roleName.toUpperCase();

			if (roleA === roleB) {
				if (a.roleId === b.roleId) {
					return 0;
				} else if (a.roleId < b.roleId) {
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

	return self;
};

launch.module.factory('ModelMapperService', function (AuthService, NotificationService) {
	return new launch.ModelMapper(AuthService, NotificationService);
});