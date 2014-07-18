launch.ContentType = function() {
	var self = this;

	self.id = null;
	self.name = null;
	self.title = null;
	self.baseType = null;

	self.allowText = function () {
		if (self.baseType === 'audio' || self.baseType === 'blog_post' || self.baseType === 'email' || self.baseType === 'long_html' ||
			self.baseType === 'photo' || self.baseType === 'social_media_post' || self.baseType === 'video') {
			return true;
		}

		//switch (self.name) {
		//	case 'blog-post':
		//	case 'casestudy':
		//	case 'ebook':
		//	case 'email':
		//	case 'facebook-post':
		//	case 'feature-article':
		//	case 'google-plus-update':
		//	case 'newsletter':
		//	case 'landing-page':
		//	case 'linkedin-update':
		//	case 'photo':
		//	case 'sales-letter':
		//	case 'sellsheet-content':
		//	case 'tweet':
		//	case 'video':
		//	case 'website-page':
		//	case 'whitepaper':
		//	case 'workflow-email':
		//		return true;
		//}

		return false;
	};

	self.requireText = function () {
		if (self.baseType === 'blog_post' || self.baseType === 'email' || self.baseType === 'long_html' || self.baseType === 'social_media_post') {
			return true;
		}

		//switch (self.name) {
		//	case 'blog-post':
		//	case 'casestudy':
		//	case 'ebook':
		//	case 'email':
		//	case 'feature-article':
		//	case 'newsletter':
		//	case 'landing-page':
		//	case 'sales-letter':
		//	case 'sellsheet-content':
		//	case 'website-page':
		//	case 'whitepaper':
		//	case 'workflow-email':
		//		return true;
		//}

		return false;
	};

	self.allowFile = function () {
		if (self.baseType === 'attached_file' || self.baseType === 'audio' || self.baseType === 'document' || self.baseType === 'email'
			|| self.baseType === 'photo' || self.baseType === 'social_media_post' || self.baseType === 'video') {
			return true;
		}

		//switch (self.name) {
		//	case 'audio-recording':
		//	case 'email':
		//	case 'facebook-post':
		//	case 'google-drive-doc':
		//	case 'google-plus-update':
		//	case 'linkedin-update':
		//	case 'photo':
		//	case 'salesforce-asset':
		//	case 'tweet':
		//	case 'video':
		//		return true;
		//}

		return false;
	};

	self.requireFile = function () {
		if (self.baseType === 'attached_file' || self.baseType === 'audio' || self.baseType === 'document'
			|| self.baseType === 'photo' || self.baseType === 'video') {
			return true;
		}

		//switch (self.name) {
		//	case 'audio-recording':
		//	case 'google-drive-doc':
		//	case 'photo':
		//	case 'salesforce-asset':
		//	case 'video':
		//		return true;
		//}

		return false;
	};

	self.allowExport = function () {
		if (self.baseType === 'blog_post' || self.baseType === 'email' || self.baseType === 'long_html') {
			return true;
		}

		//switch (self.name) {
		//	case 'blog-post':
		//	case 'email':
		//	case 'landing-page':
		//		return true;
		//}

		return false;
	};

	self.allowMetaTags = function () {
		if (self.baseType === 'long_html' || self.baseType === 'blog_post') {
			return true;
		}

		return false;
	};

	return self;
};
