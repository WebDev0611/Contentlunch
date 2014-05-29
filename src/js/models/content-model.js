launch.Content = function() {
	var self = this;
	var lowerCaseTags = null;

	self.id = null;
	self.accountId = null;
	self.author = null;
	self.title = null;
	self.contentType = null;
	self.body = null;
	self.concept = null;
	self.contentFile = null;
	self.attachments = null;

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
		if (launch.utils.isBlank(term)) {
			return true;
		}

		if (launch.utils.isValidPattern(self.title, term) || launch.utils.isValidPattern(self.body, term) || launch.utils.isValidPattern(self.concept, term)) {
			return true;
		}

		if (!lowerCaseTags) {
			lowerCaseTags = $.map(self.tags, function (t) { return t.toLowerCase(); });
		}

		if ($.isArray(lowerCaseTags) && lowerCaseTags.length > 0 && $.inArray(term.toLowerCase(), lowerCaseTags) >= 0) {
			return true;
		}

		return false;
	};

	self.validateProperty = function (property) {
		switch (property.toLowerCase()) {
			case 'title':
				return launch.utils.isBlank(self.title) ? 'Title is required.' : null;
			case 'contenttype':
				return launch.utils.isBlank(self.contentType) ? 'Content Type is required.' : null;
			case 'body':
				return launch.utils.isBlank(self.body) ? 'Description is required.' : null;
			case 'author':
				return (!self.author || launch.utils.isBlank(self.author.id)) ? 'Author is required.' : null;
			//case 'buyingstage':
				//	return (self.status >= 1 && launch.utils.isBlank(self.buyingStage)) ? 'Buying Stage is required.' : null;
			//case 'persona':
				//	return (self.status >= 1 && launch.utils.isBlank(self.persona)) ? 'Persona is required.' : null;
			case 'accountconnections':
				return (self.status >= 1 && (!$.isArray(self.accountConnections) || self.accountConnections.length === 0)) ? 'One or more Content Connections is required.' : null;
			//case 'campaign':
				//	return (!self.campaign || launch.utils.isBlank(self.campaign.id)) ? 'Campaign is required.' : null;
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
