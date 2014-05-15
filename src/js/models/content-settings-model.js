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

	self.personaProperties = ['Name', 'Column 1', 'Column 2', 'Column 3', 'Column 4', 'Column 5'];
	self.personas = [];

	self.buyingStages = function() {
		return $.grep(self.personaProperties, function(pp, i) {
			return (!launch.utils.isBlank(pp) && pp.toLowerCase() !== 'name');
		});
	};

	self.addEmptyPerona = function() {
		var properties = [];

		for (var i = 0; i < self.personaProperties.length; i++) {
			var text = launch.utils.isBlank(self.personaProperties[i]) ? null : 'New ' + self.personaProperties[i];
			properties.push({ index: i, value: text });
		}

		self.personas.push({
			properties: properties
		});
	};

	self.deletePersona = function(index) {
		self.personas.splice(index, 1);
	};

	return self;
};