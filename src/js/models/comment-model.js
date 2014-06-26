launch.Comment = function() {
	var self = this;

	self.id = null;
	self.itemId = null;
	self.comment = null;
	self.commentor = null;
	self.isGuestComment = false;
	self.created = null;
	self.updated = null;

	self.commentDate = function () {
		return launch.utils.formatDateTime(self.updated);
	};

	self.validateProperty = function (property) {
		switch (property.toLowerCase()) {
			case 'comment':
				return launch.utils.isBlank(this.comment) ? 'Comment is required.' : null;
			case 'commentor':
				return (!this.commentor || launch.utils.isBlank(this.commentor.id)) ? 'Commentor is required.' : null;
			default:
				return null;
		}
	};

	return self;
};