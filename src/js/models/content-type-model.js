launch.ContentType = function() {
	var self = this;

	self.id = null;
	self.name = null;
	self.title = null;
	self.baseType = null;

	self.allowText = function () {
		return (self.baseType === 'audio' || self.baseType === 'blog_post' || self.baseType === 'email' || self.baseType === 'long_html' ||
				self.baseType === 'photo' || self.baseType === 'social_media_post' || self.baseType === 'video');
	};

	self.requireText = function () {
		return  (self.baseType === 'blog_post' || self.baseType === 'email' || self.baseType === 'long_html' || self.baseType === 'social_media_post');
	};

	self.allowFile = function () {
		return (self.baseType === 'attached_file' || self.baseType === 'audio' || self.baseType === 'document' || self.baseType === 'email' ||
				self.baseType === 'photo' || self.baseType === 'social_media_post' || self.baseType === 'video');
	};

	self.requireFile = function () {
		return (self.baseType === 'attached_file' || self.baseType === 'audio' || self.baseType === 'document' ||
				self.baseType === 'photo' || self.baseType === 'video');
	};

	self.allowExport = function () {
		return (self.baseType === 'blog_post' || self.baseType === 'email' || self.baseType === 'long_html');
	};

	self.allowMetaTags = function () {
		return (self.baseType === 'long_html' || self.baseType === 'blog_post');
	};

	return self;
};
