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
  self.tags = null;

	self.fileType = function () {
		return launch.utils.getFileTypeCssClass(self.extension);
	};

	self.isImage = function () {
		if (!launch.utils.isBlank(self.extension)) {
			var ext = self.extension.toLowerCase();

			if (ext === 'jpg' || ext === 'jpeg' || ext === 'png' || ext === 'gif' || ext === 'bmp' ||
				ext === 'tif' || ext === 'tiff' || ext === 'svg' || ext === 'jif' || ext === 'jiff') {
				return true;
			}
		}

		return false;
	};

	self.isVideo = function () {
		if (!launch.utils.isBlank(self.extension)) {
			var ext = self.extension.toLowerCase();

			if (ext === 'mp4' || ext === 'avi' || ext === 'mov' || ext === 'swf' || ext === 'wmv' ||
				ext === 'm1v' || ext === 'm2v' || ext === 'mpeg' || ext === 'aaf') {
				return true;
			}
		}

		return false;
	};

	self.isAudio = function () {
		if (!launch.utils.isBlank(self.extension)) {
			var ext = self.extension.toLowerCase();

			if (ext === 'mp3' || ext === 'm4a' || ext === 'pac' || ext === 'wav' || ext === 'aif' ||
				ext === 'aiff' || ext === 'flac' || ext === 'mp2' || ext === 'wma' || ext === 'aac' ||
				ext === 'ra' || ext === 'rm' || ext === 'swa' || ext === 'vox' || ext === 'voc' || ext === 'asf') {
				return true;
			}
		}

		return false;
	};

	return self;
};