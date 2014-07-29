﻿launch.Content = function() {
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
	self.activity = null;

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
	self.convertDate = null;
	self.submitDate = null;
	self.approveDate = null;
	self.launchDate = null;
	self.promoteDate = null;
	self.archiveDate = null;
	self.created = null;
	self.updated = null;

	self.collaborators = null;
	self.comments = null;
	self.relatedContent = null;
	self.tags = null;
	self.metaDescription = null;
	self.metaKeywords = null;
	self.ecommercePlatform = null;
	self.isSelected = false;
	self.contentScore = null;

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
				return 'launch';
			case 3:
				return 'promote';
			case 4:
				return 'archive';
			default:
				return null;
		}
	};

	self.currentStepTitleCase = function() {
		return launch.utils.titleCase(self.currentStep());
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
				return launch.utils.isBlank(this.title) ? 'Title is required.' : null;
			case 'contenttype':
				return (!this.contentType || launch.utils.isBlank(this.contentType.name)) ? 'Content Type is required.' : null;
			case 'body':
				if (!this.contentType || launch.utils.isBlank(this.contentType.name)) {
					return self.validateProperty('contenttype');
				}

				if (self.contentType.requireText()) {
					return launch.utils.isBlank(this.body) ? 'Description is required.' : null;
				}
			case 'author':
				return (!this.author || launch.utils.isBlank(this.author.id)) ? 'Author is required.' : null;
			case 'contentfile':
				if (!this.contentType || launch.utils.isBlank(this.contentType.name)) {
					return null;
				}

				return self.validateContentFile();
			case 'ecommerceplatform':
				if (!this.contentType || launch.utils.isBlank(this.contentType.name)) {
					return null;
				}

				if (this.contentType.name === 'product-description' && launch.utils.isBlank(this.ecommercePlatform)) {
					return 'Ecommerce Platform is required for Product Description content types.';
				}
			default:
				return null;
		}
	};

	self.validateContentFile = function (file) {
		//if (self.contentType.requireFile()) {
		//	if (!self.contentFile) {
		//		return 'Content File is required.';
		//	}
		//} else if (!self.contentFile) {
		//	return null;
		//}

		//if (launch.utils.isBlank(self.contentFile.id)) {
		//	return 'Invalid Content File specified.';
		//}

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
