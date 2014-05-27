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
	self.created = null;
	self.updated = null;
	self.deleted = null;

	self.fileType = function() {
		if (launch.utils.isBlank(self.extension)) {
			return null;
		}

		switch (self.extension.toLowerCase()) {
			case 'jpg':
			case 'gif':
			case 'png':
			case 'jpeg':
				return 'image';
			case 'avi':
			case 'mp4':
			case 'mov':
				return 'video';
			case 'wav':
			case 'mp3':
			case 'wma':
				return 'audio';
			case 'pdf':
				return 'pdf';
			case 'doc':
			case 'docx':
				return 'ms-word';
			case 'xls':
			case 'xlsx':
				return 'ms-excel';
			case 'ppt':
			case 'pptx':
				return 'ms-powerpoint';
			default:
				return null;
		}
	};

	return self;
};