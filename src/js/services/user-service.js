
launch.module.factory('UserService', function($resource) {
	var map = {
		parseResponse: function(r, getHeaders) {
			var dto = JSON.parse(r);

			if ($.isArray(dto)) {
				var users = [];

				angular.forEach(dto, function(user, index) {
					users.push(map.fromDto(user));
				});

				users.sort(function(a, b) {
					if (launch.utils.isBlank(a.lastName) && launch.utils.isBlank(b.lastName) &&
						launch.utils.isBlank(a.firstName) && launch.utils.isBlank(b.firstName)) {
						return 0;
					}

					if (a.lastName === b.lastName) {
						if (launch.utils.isBlank(a.firstName) && launch.utils.isBlank(b.firstName)) {
							return 0;
						} else if (a.firstName === b.firstName) {
							return 0;
						} else if (a.firstName < b.firstName) {
							return -1;
						} else {
							return 1;
						}
					} else {
						if (a.lastName < b.lastName) {
							return -1;
						} else {
							return 1;
						}
					}
				});

				return users;
			}

			if ($.isPlainObject(dto)) {
				return map.fromDto(dto);
			}

			return null;
		},
		fromDto: function(dto) {
			var user = new User();

			user.id = dto.id;
			user.userName = dto.userName;
			user.firstName = dto.first_name;
			user.lastName = dto.last_name;
			user.email = dto.email;
			user.created = dto.created_at;
			user.updated = dto.updated_at;
			user.confirmed = dto.confirmed;
			user.role = 'USER #' + user.id + '\'S ROLE';
			//user.image = '/assets/images/testing-user-image.png';

			return user;
		},
		toDto: function (user) {
			return JSON.stringify({
				id: user.id,
				userName: user.userName,
				first_name: user.firstName,
				last_name: user.lastName,
				email: user.email,
				created_at: user.created,
				updated_at: user.updated,
				confirmed: user.confirmed
			});
		}
	};

	var resource = $resource('/api/user/:id', { id: '@id' }, {
		get: { method: 'GET', transformResponse: map.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: map.parseResponse },
		update: { method: 'PUT', transformRequest: map.toDto, transformResponse: map.parseResponse }
	});

	var User = function() {
		var self = this;

		self.formatName = function() {
			if (!launch.utils.isBlank(self.firstName) && !launch.utils.isBlank(self.lastName)) {
				return self.firstName + ' ' + self.lastName;
			}

			if (!launch.utils.isBlank(self.userName)) {
				return self.userName;
			}

			if (!launch.utils.isBlank(self.email)) {
				return self.email;
			}

			return self.id;
		};

		self.matchSearchTerm = function(term) {
			if (launch.utils.startsWith(self.userName, term) || launch.utils.startsWith(self.firstName, term) ||
				launch.utils.startsWith(self.lastName, term) || launch.utils.startsWith(self.email, term)) {
				return true;
			}

			return false;
		};

		self.hasImage = function() { return !launch.utils.isBlank(self.image); };
		self.imageUrl = function() { return self.hasImage() ? 'url(\'' + self.image + '\')' : null; };

		self.id = null;
		self.userName = null;
		self.firstName = null;
		self.lastName = null;
		self.email = null;
		self.created = null;
		self.updated = null;
		self.confirmed = null;
		self.role = null;

		self.validateProperty = function(property) {
			switch (property) {
				case 'firstName':
					return launch.utils.isBlank(this.firstName) ? 'First Name is required.' : null;
				case 'lastName':
					return launch.utils.isBlank(this.lastName) ? 'Last Name is required.' : null;
				case 'email':
					if (launch.utils.isBlank(this.email)) {
						return 'Email is required.';
					} else if (!launch.utils.isValidEmail(this.email)) {
						return 'Please enter a valid email address.';
					}

					return null;
				default:
					return null;
			}
		};

		self.validate = function () {
			var properties = Object.keys(this);

			for (var i = 0; i < properties.length; i++) {
				if (!launch.utils.isBlank(this.validateProperty(properties[i]))) {
					return false;
				}
			}

			return true;
		};

		return self;
	};

	return {
		query: function(params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.query(params, success, error);
		},
		get: function(params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.get(params, success, error);
		},
		update: function (user, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return resource.update({ id: user.id }, user, success, error);
		},
		getNewUser: function() {
			return new User();
		}
	};
});
