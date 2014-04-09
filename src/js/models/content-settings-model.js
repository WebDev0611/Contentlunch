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

	self.personaProperties = ['Name', 'Suspects', 'Prospects', 'Lead', 'Opportunities'];
	self.personas = [
		{
			editing: false,
			properties: [
				{ index: 0, value: 'CMO' },
				{ index: 1, value: 'Description of how a CMO acts at the Suspect Stage Description of how a CMO acts at the Suspect Stage' },
				{ index: 2, value: 'Description of how a CMO acts at the Prospects Stage Description of how a CMO acts at the Prospects Stage' },
				{ index: 3, value: 'Description of how a CMO acts at the Lead Stage Description of how a CMO acts at the Lead Stage' },
				{ index: 4, value: 'Description of how a CMO acts at the Opportunities Stage Description of how a CMO acts at the Opportunities Stage' }
			]
		},
		{
			editing: false,
			properties: [
				{ index: 0, value: 'VP Sales' },
				{ index: 1, value: 'Description of how a VP Sales acts at the Suspect Stage Description of how a VP Sales acts at the Suspect Stage' },
				{ index: 2, value: 'Description of how a VP Sales acts at the Prospects Stage Description of how a VP Sales acts at the Prospects Stage' },
				{ index: 3, value: 'Description of how a VP Sales acts at the Lead Stage Description of how a VP Sales acts at the Lead Stage' },
				{ index: 4, value: 'Description of how a VP Sales acts at the Opportunities Stage Description of how a VP Sales acts at the Opportunities Stage' }
			]
		},
		{
			editing: false,
			properties: [
				{ index: 0, value: 'Sales Rep' },
				{ index: 1, value: 'Description of how a Sales Rep acts at the Suspect Stage Description of how a Sales Rep acts at the Suspect Stage' },
				{ index: 2, value: 'Description of how a Sales Rep acts at the Prospects Stage Description of how a Sales Rep acts at the Prospects Stage' },
				{ index: 3, value: 'Description of how a Sales Rep acts at the Lead Stage Description of how a Sales Rep acts at the Lead Stage' },
				{ index: 4, value: 'Description of how a Sales Rep acts at the Opportunities Stage Description of how a Sales Rep acts at the Opportunities Stage' }
			]
		},
		{
			editing: false,
			properties: [
				{ index: 0, value: 'Product Manager' },
				{ index: 1, value: 'Description of how a Product Manager acts at the Suspect Stage Description of how a Product Manager acts at the Suspect Stage' },
				{ index: 2, value: 'Description of how a Product Manager acts at the Prospects Stage Description of how a Product Manager acts at the Prospects Stage' },
				{ index: 3, value: 'Description of how a Product Manager acts at the Lead Stage Description of how a Product Manager acts at the Lead Stage' },
				{ index: 4, value: 'Description of how a Product Manager acts at the Opportunities Stage Description of how a Product Manager acts at the Opportunities Stage' }
			]
		}
	];

	self.addPersonaProperty = function(name, defaultValue) {
		var length = self.personaProperties.length;

		self.personaProperties.push(name);

		for (var i = 0; i < self.personas.length; i++) {
			self.personas[i].properties.push({ index: length, value: (launch.utils.isBlank(defaultValue) ? null : defaultValue) });
		}
	};

	self.editPersonaProperty = function(name, index) {
		self.personaProperties[index] = name;
	};

	self.deletePersona = function(index) {
		self.personas.splice(index, 1);
	};

	return self;
};