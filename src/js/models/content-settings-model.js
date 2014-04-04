launch.ContentSettings = function () {
	var self = this;

	self.useCms = null;
	self.includeAuthorName = false;
	self.allowPublishDateEdit = false;
	self.useKeywordTags = false;
	self.publishingGuidelines = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum claritatem. Investigationes demonstraverunt lectores legere me lius quod ii legunt saepius. Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum.';

	self.personas = [
		{ 'Name': 'CMO', 'Suspects': 'Description of how a CMO acts  at the Suspect Stage Description of how a CMO acts  at the Suspect Stage', 'Prospects': 'Description of how a CMO acts  at the Suspect Stage Description of how a CMO acts  at the Suspect Stage', 'Lead': 'Description of how a CMO acts  at the Suspect Stage Description of how a CMO acts  at the Suspect Stage', 'Opportunities': 'Description of how a CMO acts  at the Suspect Stage Description of how a CMO acts  at the Suspect Stage' },
		{ 'Name': 'VP Sales', 'Suspects': 'Description of how a VP Sales acts  at the Suspect Stage Description of how a VP Sales acts  at the Suspect Stage', 'Prospects': 'Description of how a VP Sales acts  at the Suspect Stage Description of how a VP Sales acts  at the Suspect Stage', 'Lead': 'Description of how a VP Sales acts  at the Suspect Stage Description of how a VP Sales acts  at the Suspect Stage', 'Opportunities': 'Description of how a VP Sales acts  at the Suspect Stage Description of how a VP Sales acts  at the Suspect Stage' },
		{ 'Name': 'Sales Rep', 'Suspects': 'Description of how a Sales Rep acts  at the Suspect Stage Description of how a Sales Rep acts  at the Suspect Stage', 'Prospects': 'Description of how a Sales Rep acts  at the Suspect Stage Description of how a Sales Rep acts  at the Suspect Stage', 'Lead': 'Description of how a Sales Rep acts  at the Suspect Stage Description of how a Sales Rep acts  at the Suspect Stage', 'Opportunities': 'Description of how a Sales Rep acts  at the Suspect Stage Description of how a Sales Rep acts  at the Suspect Stage' },
		{ 'Name': 'Product Manager', 'Suspects': 'Description of how a Product Manager acts  at the Suspect Stage Description of how a Product Manager acts  at the Suspect Stage', 'Prospects': 'Description of how a Product Manager acts  at the Suspect Stage Description of how a Product Manager acts  at the Suspect Stage', 'Lead': 'Description of how a Product Manager acts  at the Suspect Stage Description of how a Product Manager acts  at the Suspect Stage', 'Opportunities': 'Description of how a Product Manager acts  at the Suspect Stage Description of how a Product Manager acts  at the Suspect Stage' }
	];

	self.getPersonaProperties = function() {
		if (!$.isArray(self.personas) || self.personas.length === 0) {
			return [];
		}

		return $.grep(Object.keys(self.personas[0]), function(p, i) {
			return !launch.utils.startsWith(p, '$');
		});
	};

	self.addPersonaProperty = function (name, defaultValue) {
		if (launch.utils.isBlank(name)) {
			return;
		}

		var newPersonas = [];

		angular.forEach(self.personas, function(p, i) {
			var json = JSON.stringify(p);

			json = json.replace('}', ', "' + name + '": null }');

			newPersonas.push(JSON.parse(json));
		});

		self.personas = newPersonas;
	};

	return self;
};