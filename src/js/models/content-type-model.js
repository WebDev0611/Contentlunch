launch.ContentType = function() {
	var self = this;

	self.id = null;
	self.name = null;
	self.title = null;

	self.allowText = function () {
		switch (self.name) {
			case 'blog-post':
			case 'casestudy':
			case 'ebook':
			case 'email':
			case 'facebook-post':
			case 'feature-article':
			case 'google-plus-update':
			case 'newsletter':
			case 'landing-page':
			case 'linkedin-update':
			case 'photo':
			case 'sales-letter':
			case 'sellsheet-content':
			case 'tweet':
			case 'video':
			case 'website-page':
			case 'whitepaper':
			case 'workflow-email':
				return true;
		}

		return false;
	};

	self.requireText = function () {
		switch (self.name) {
			case 'blog-post':
			case 'casestudy':
			case 'ebook':
			case 'email':
			case 'feature-article':
			case 'newsletter':
			case 'landing-page':
			case 'sales-letter':
			case 'sellsheet-content':
			case 'website-page':
			case 'whitepaper':
			case 'workflow-email':
				return true;
		}

		return false;
	};

	self.allowFile = function () {
		switch (self.name) {
			case 'audio-recording':
			case 'email':
			case 'facebook-post':
			case 'google-drive-doc':
			case 'google-plus-update':
			case 'linkedin-update':
			case 'photo':
			case 'salesforce-asset':
			case 'tweet':
			case 'video':
				return true;
		}

		return false;
	};

	self.requireFile = function () {
		switch (self.name) {
			case 'audio-recording':
			case 'google-drive-doc':
			case 'photo':
			case 'salesforce-asset':
			case 'video':
				return true;
		}

		return false;
	};

	return self;
};
