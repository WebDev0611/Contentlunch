launch.Content = function() {
	var self = this;

	self.id = null;
	self.title = null;
	self.body = null;
	self.accountId = null;
	self.userId = null;
	self.buyingStage = null;
	self.persona = null;
	self.campaignId = null;
	self.secondaryBuyingStage = null;
	self.secondaryPersona = null;
	self.concept = null;
	self.status = null;
	self.archived = null;
	self.dueDate = null;
	self.created = null;
	self.updated = null;

	self.campaign = null;
	self.collaborators = null;
	self.comments = null;
	self.contentType = null;
	self.accountConnections = null;
	self.related = null;
	self.tags = null;
	self.user = null;

	self.currentStep = function() {
		if (self.archived === true) {
			return 'archive';
		}

		switch (self.status) {
			case 0:
				return 'concept';
			case 1:
				return 'create';
			case 2:
				return 'edit';
			case 3:
				return 'approve';
			case 4:
				return 'launch';
			default:
				return null;
		}
	};

	self.nextStep = function () {
		if (self.archived === true) {
			return 'restore';
		}

		switch (self.status) {
			case 0:
				return 'create';
			case 1:
				return 'edit';
			case 2:
				return 'approve';
			case 3:
				return 'launch';
			case 4:
				return 'archive';
			default:
				return null;
		}
	};

	self.matchSearchTerm = function(term) {
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
			case 'title':
				return launch.utils.isBlank(this.title) ? 'Title is required.' : null;
			case 'contenttype':
				return launch.utils.isBlank(this.contentType) ? 'Content Type is required.' : null;
			case 'body':
				return launch.utils.isBlank(this.body) ? 'Description is required.' : null;
			case 'creator':
				return (!this.creator || launch.utils.isBlank(this.creator.id)) ? 'Creator is required.' : null;
			default:
				return null;
		}
	};

	return self;
};
