launch.UploadFile = function () {
	var self = this;

	self.id = null;
	self.accountId = null;
	self.userId = null;
	self.parentId = null;
	self.extension = null;
	self.fileName = null;
	self.mimeType = null;
	self.path = null;
	self.size = 0;
	self.description = null;
	self.created = null;
	self.updated = null;
	self.deleted = null;

	self.fileType = function () {
		return launch.utils.getFileTypeCssClass(self.extension);
	};

	return self;
};