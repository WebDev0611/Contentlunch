launch.Campaign = function() {
	var self = this;

	self.id = null;
	self.accountId = null;
	self.user = null;

	self.title = null;
	self.description = null;
	self.concept = null;
	self.campaignType = null;
	self.type = null;
	self.status = null;
	self.goals = null;
	self.color = null;

	self.startDate = null;
	self.endDate = null;
	self.isRecurring = null;
	self.isSeries = null;
	self.recurringId = null;

	self.contact = null;
	self.host = null;
	self.speakerName = null;
	self.partners = null;
	self.audioLink = null;

	self.linkNeeded = null;
	self.photoNeeded = null;

	self.tags = null;
	self.collaborators = null;
	self.comments = null;
	self.guestCollaborators = null;

	self.created = null;
	self.updated = null;

	self.index = function () {
		return launch.utils.getCampaignIndex(self.id);
	};

	self.validateProperty = function (property) {
		switch (property.toLowerCase()) {
			case 'title':
				return launch.utils.isBlank(self.title) ? 'Title is required.' : null;
			case 'description':
				return launch.utils.isBlank(self.description) ? 'Description is required.' : null;
			case 'user':
				return (!self.user || launch.utils.isBlank(self.user.id)) ? 'Campaign Creator is required.' : null;
			default:
				return null;
		}
	};

	return self;
};