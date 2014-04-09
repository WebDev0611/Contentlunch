launch.ContentSettings = function () {
	var self = this;

	self.useCms = null;
	self.includeAuthorName = false;
	self.authorNameContentTypes = ['Audio', 'Ebook', 'Google Drive', 'Photo', 'Video'];
	self.allowPublishDateEdit = false;
	self.publishDateContentTypes = ['Blog Post', 'Email', 'Landing Page', 'Twitter', 'Whitepaper'];
	self.useKeywordTags = false;
	self.keywordTagsContentTypes = ['Case Study', 'Facebook Post', 'LinkedIn', 'Salesforce Asset'];
	self.publishingGuidelines = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum claritatem. Investigationes demonstraverunt lectores legere me lius quod ii legunt saepius. Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum.';

	self.personaProperties = ['Name', 'Column 1', 'Column 2', 'Column 3', 'Column 4', 'Column 5'];
	self.personas = [];

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

	self.editPersonaProperty = function(name, index) {
		self.personaProperties[index] = name;
	};

	self.deletePersona = function(index) {
		self.personas.splice(index, 1);
	};

	return self;
};