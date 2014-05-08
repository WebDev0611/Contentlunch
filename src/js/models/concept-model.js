launch.Concept = function () {
	var self = this;

	self.id = null;
	self.title = null;
	self.contentType = null;
	self.description = null;
	self.campaign = null;
	self.creator = null;
	self.collaborators = null;

	self.validateProperty = function (property) {
		switch (property.toLowerCase()) {
			case 'title':
				return launch.utils.isBlank(this.title) ? 'Title is required.' : null;
			case 'contenttype':
				return launch.utils.isBlank(this.title) ? 'Content Type is required.' : null;
			case 'description':
				return launch.utils.isBlank(this.title) ? 'Description is required.' : null;
			case 'creator':
				return launch.utils.isBlank(this.title) ? 'Creator is required.' : null;
			default:
				return null;
		}
	};

	return self;
};

