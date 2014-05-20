launch.Campaign = function() {
	var self = this;

	self.id = null;
	self.title = null;
	self.description = null;
	self.goals = null;
	self.campaignType = null;

	self.startDate = null;
	self.endDate = null;
	self.isRecurring = false;
	self.tags = null;

	self.user = null;

	self.index = function () {
		return launch.utils.getCampaignIndex(self.id);
	};

	return self;
};