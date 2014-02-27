
launch.module.factory('UserService', function ($resource) {
	var map = {
		parseResponse: function(r, a, b, c) {
			var dto = JSON.parse(r);

			if ($.isArray(dto)) {
				var users = [];

				angular.forEach(dto, function (user, index) {
					users.push(map.fromDto(user));
				});

				return users;
			}

			if ($.isPlainObject(dto)) {
				return map.fromDto(dto);
			}

			return null;
		},
		fromDto: function (dto) {
			var user = new User();

			user.id = dto.id;
			user.userName = dto.userName;
			user.firstName = dto.first_name;
			user.lastName = dto.last_name;
			user.email = dto.email;
			user.created = dto.created_at;
			user.updated = dto.updated_at;
			user.confirmed = dto.confirmed;

			return user;
		},
		toDto: function (user) {
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

	var User = function () {
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

		return self;
	};

	return {
		query: function(params) {
      return resource.query(params);
    },
		get: function (params) {
			return resource.get(params);
		}
	};
});
