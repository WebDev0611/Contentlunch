launch.Content = function() {
	var self = this;

	self.id = null;
	self.title = null;
	self.campaign = null;
	self.contentType = null;
	self.author = null;
	self.persona = null;
	self.buyingStage = null;
	self.currentStep = null;
	self.nextStep = null;

	self.matchSearchTerm = function (term) {
		//if (launch.utils.startsWith(self.title, term) || launch.utils.startsWith(self.email, term) ||
		//	launch.utils.startsWith(self.city, term) ||
		//	launch.utils.startsWith(self.state.name, term) || launch.utils.startsWith(self.state.value, term)) {
		//	return true;
		//}

		//if (!!self.subscription) {
		//	var level = !isNaN(term) ? term : term.replace(/^tier /, '').replace(/^tier/, '').replace(/^tie/, '').replace(/^ti/, '').replace(/^t/, '');

		//	return self.subscription.subscriptionLevel === parseInt(level);
		//}

		return false;
	};

	self.validateProperty = function (property) {
		switch (property.toLowerCase()) {
			default:
				return null;
		}
	};

	return self;
};
