launch.Role = function(id, name) {
	var self = this;

	self.roleId = parseInt(id);
	self.roleName = name;

	self.isGlobalAdmin = function() {
		return (!launch.utils.isBlank(self.roleName) && self.roleName.toUpperCase() === 'ADMIN');
	};

	return self;
};