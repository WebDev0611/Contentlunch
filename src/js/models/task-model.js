launch.TaskGroup = function () {
	var self = this;

	self.id = null;
	self.contentId = null;
	self.status = null;
	self.isComplete = false;
	self.dueDate = null;
	self.completeDate = null;
	self.tasks = null;
	self.created = null;
	self.updated = null;

	self.isOverdue = function(currentStatus) {
		if (currentStatus > self.status) {
			return false;
		}

		return self.dueDate < (new Date());
	};

	self.formattedDueDate = function () {
		return launch.utils.formatDate(self.dueDate);
	};

	self.formattedCompleteDate = function () {
		var date = launch.utils.formatDate(self.completeDate);

		if (launch.utils.isBlank(date) && $.isArray(self.tasks) && self.tasks.length > 0) {
			$.each(self.tasks, function(i, t) {
				if (!launch.utils.isBlank(t.completeDate) && (launch.utils.isBlank(date) || t.completeDate > date)) {
					date = t.completeDate;
				}
			});

			date = launch.utils.formatDate(date);
		}

		return date;
	};

	self.name = function () {
		switch (self.status) {
			case 0:
				return 'Concept';
			case 1:
				return 'Create';
			case 2:
				return 'Review';
			case 3:
				return 'Launch';
			case 4:
				return 'Promote';
			default:
				return null;
		}
	};

	self.validateProperty = function(property) {
		switch (property.toLowerCase()) {
			case 'contentid':
				return launch.utils.isBlank(this.contentId) ? 'Content item is not specified.' : null;
			case 'status':
				if (launch.utils.isBlank(this.status)) {
					return 'Status is not specified.';
				} else if (isNaN(this.status) || this.status < 0 || this.status > 5) {
					return 'Status is not valid.';
				}

				return null;
			case 'duedate':
				if (launch.utils.isBlank(this.dueDate)) {
					return 'Due Date is not specified.';
				} else if (!launch.utils.isValidDate(this.dueDate)) {
					return 'Due Date is not valid.';
				}

				return null;
			case 'tasks':
				var msg = '';

				if ($.isArray(this.tasks) && this.tasks.length > 0) {
					$.each(this.tasks, function (i, t) {
						//var dateMsg = (t.dueDate > self.dueDate) ? 'Task ' + t.name + ' due date is later than ' + self.name() + ' due date.' : null;
						var subMsg = launch.utils.validateAll(t, 'Task ' + t.name + ' ');

						//if (!launch.utils.isBlank(dateMsg)) {
						//	msg += dateMsg + '\n';
						//}

						if (!launch.utils.isBlank(subMsg)) {
							msg += subMsg + '\n';
						}
					});
				}

				return launch.utils.isBlank(msg) ? null : msg;
			default:
				return null;
		}
	};

	return self;
};

launch.Task = function() {
	var self = this;

	self.id = null;
	self.name = null;
	self.isComplete = false;

	self.dueDate = null;
	self.completeDate = null;
	self.userId = null;
	self.taskGroupId = null;
	self.created = null;
	self.updated = null;

	self.isOverdue = function () {
		if (self.isComplete) {
			return false;
		}

		return self.dueDate < (new Date());
	};

	self.formattedDueDate = function () {
		return launch.utils.formatDate(self.dueDate);
	};

	self.formattedCompleteDate = function () {
		return launch.utils.formatDate(self.completeDate);
	};

	self.validateProperty = function(property) {
		switch (property.toLowerCase()) {
			case 'name':
				return launch.utils.isBlank(this.name) ? 'Task Name is required.' : null;
			case 'duedate':
				if (launch.utils.isBlank(this.dueDate)) {
					return 'Due Date is not specified.';
				} else if (!launch.utils.isValidDate(this.dueDate)) {
					return 'Due Date is not valid.';
				}

				return null;
			case 'userid':
				return launch.utils.isBlank(this.userId) ? 'Assignee is required.' : null;
			case 'taskgroupid':
				return launch.utils.isBlank(this.taskGroupId) ? 'Task Group is required.' : null;
			default:
				return null;
		}
	};

	return self;
};