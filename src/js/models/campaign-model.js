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
		var index = 0;

		if (!isNaN(self.id)) {
			if (self.id <= 15) {
				index = self.id;
			} else {
				index = ((self.id % 15) + 1);
			}
		}

		return index + 1;
	};

	return self;
};