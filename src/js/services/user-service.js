
launch.module.factory('UserService', function($resource) {
	var map = {
		parseResponse: function(r, getHeaders) {
			var dto = JSON.parse(r);

			if ($.isArray(dto)) {
				var users = [];

				angular.forEach(dto, function(user, index) {
					users.push(map.fromDto(user));
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
		toDto: function(user) {
			return {
				id: user.id,
				userName: user.userName,
				first_name: user.firstName,
				last_name: user.lastName,
				email: user.email,
				created_at: user.created,
				updated_at: user.updated,
				confirmed: user.confirmed
			};
		}
	};

	var resource = $resource('/api/user/:id', { id: '@id' }, {
		get: { method: 'GET', transformResponse: map.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: map.parseResponse }
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

		self.hasImage = function() { return !launch.utils.isBlank(self.image); };
		self.imageUrl = function() { return self.hasImage() ? 'url(\'' + self.image + '\')' : null; };

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
		}
	};
});
