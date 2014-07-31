launch.Pagination = function (defaultSort, defaultSortDirection) {
	var self = this;

	self.totalItems = 0;
	self.currentSort = launch.utils.isBlank(defaultSort) ? null : defaultSort;
	self.currentSortDirection = (defaultSortDirection === 'desc') ? 'desc' : 'asc';
	self.pageSize = 5;
	self.currentPage = 1;
	self.maxPage = 0;
	self.pagedContent = null;
	self.onPageChange = null;

	self.groupToPages = function (filteredContent) {
		self.pagedContent = [];

		if ($.isArray(filteredContent) && filteredContent.length > 0) {
			for (var i = 0; i < filteredContent.length; i++) {
				if (i % self.pageSize === 0) {
					self.pagedContent[Math.floor(i / self.pageSize)] = [filteredContent[i]];
				} else {
					self.pagedContent[Math.floor(i / self.pageSize)].push(filteredContent[i]);
				}
			}
		}

		self.maxPage = self.pagedContent.length;
	};

	self.getPageIndicator = function() {
		var start = (((self.currentPage - 1) * self.pageSize) + 1);
		var end = (self.currentPage * self.pageSize);

		if (end > self.totalItems) {
			end = self.totalItems;
		}

		return start + ' to ' + end + ' of ' + self.totalItems;
	};

	self.reset = function (sort, direction) {
		if (!launch.utils.isBlank(sort)) {
			self.currentSort = sort;
		}

		self.currentSortDirection = (direction === 'desc' ? 'desc' : 'asc');

		self.currentPage = 1;
	};

	return self;
};