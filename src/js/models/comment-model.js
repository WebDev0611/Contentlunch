launch.Comment = function() {
	var self = this;

	self.id = null;
	self.commentor = null;
	self.comment = null;
	self.commentDate = null;
	self.contentId = null;
	self.contentType = null;

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