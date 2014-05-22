launch.Content = function() {
	var self = this;

	self.id = null;
	self.accountId = null;
	self.author = null;
	self.title = null;
	self.contentType = null;
	self.body = null;
	self.concept = null;

	self.accountConnections = null;
	self.accountConnection = null;

	self.buyingStage = null;
	self.persona = null;
	self.campaign = null;
	self.secondaryBuyingStage = null;
	self.secondaryPersona = null;

	self.status = null;
	self.archived = null;
	self.dueDate = null;
	self.created = null;
	self.updated = null;

	self.collaborators = null;
	self.comments = null;
	self.relatedContent = null;
	self.tags = null;

	self.taskGroups = null;

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
				return 'review';
			case 3:
				return 'launch';
			case 4:
				return 'promote';
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
				return 'review';
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

	self.matchSearchTerm = function (term) {
		// TODO: IMPLEMENT THIS METHOD TO MATCH A CONTENT ITEM'S SEARCH TERM!!

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
			case 'author':
				return (!this.author || launch.utils.isBlank(this.author.id)) ? 'Author is required.' : null;
			//case 'buyingstage':
			//	return (this.status >= 1 && launch.utils.isBlank(this.buyingStage)) ? 'Buying Stage is required.' : null;
			//case 'persona':
			//	return (this.status >= 1 && launch.utils.isBlank(this.persona)) ? 'Persona is required.' : null;
			case 'accountconnections':
				return (this.status >= 1 && (!$.isArray(this.accountConnections) || this.accountConnections.length === 0)) ? 'One or more Content Connections is required.' : null;
			//case 'campaign':
			//	return (!this.campaign || launch.utils.isBlank(this.campaign.id)) ? 'Campaign is required.' : null;
			default:
				return null;
		}
	};

	self.validateContentFile = function (file) {
		// TODO: ADD FILE TYPE VALIDATION HERE!!
		var supportedFiles = launch.config.USER_PHOTO_FILE_TYPES;

		//if ($.inArray(file.type, supportedFiles) < 0) {
		//	return 'The file you selected is not supported. You may only upload JPG, PNG, GIF, or BMP images.';
		//} else if (file.size > 5000000) {
		//	return 'The file you selected is too big. You may only upload files that are 5MB or less.';
		//}

		return null;
	};

	return self;
};
