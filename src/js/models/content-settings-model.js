launch.ContentSettings = function () {
	var self = this;

	self.id = null;
	self.accountId = null;
	self.includeAuthorName = false;
	self.authorNameContentTypes = null;
	self.allowPublishDateEdit = false;
	self.publishDateContentTypes = null;
	self.useKeywordTags = false;
	self.keywordTagsContentTypes = null;
	self.publishingGuidelines = null;
	self.created = null;
	self.updated = null;

	self.personaProperties = ['Column 1', 'Column 2', 'Column 3', 'Column 4', 'Column 5'];
	self.personas = [];

	self.buyingStages = function() {
		return $.grep(self.personaProperties, function(pp, i) {
			return (!launch.utils.isBlank(pp) && pp.toLowerCase() !== 'name');
		});
	};

	self.addEmptyPerona = function(index) {
		var properties = [];

		for (var i = 0; i < this.personaProperties.length; i++) {
			var text = launch.utils.isBlank(this.personaProperties[i]) ? null : 'New ' + this.personaProperties[i];
			properties.push({ index: i, value: text });
		}

		index = parseInt(index);

		if (isNaN(index) || index >= this.personas.length || index < 0) {
			this.personas.push({
				properties: properties
			});

		} else {
			this.personas.splice(index, 0, {
				properties: properties
			});

		}
	};

	self.deletePersona = function(index) {
		this.personas.splice(index, 1);
	};

	self.validateProperty = function (property) {
		if (launch.utils.isBlank(property)) {
			return null;
		}

		if (property.toLowerCase() === 'publishingguidelines' && launch.utils.isBlank(this.publishingGuidelines)) {
			return 'Publishing Guidelines are required.';
		}

		return null;
	};

	return self;
};