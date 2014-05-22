launch.module.factory('TaskService', function ($resource, ModelMapperService) {
	var contentTasks = $resource('/api/account/:accountId/content/:contentId/task-group/:id', { accountId: '@accountId', contentId: '@contentId', id: '@id' },
	{
		get: { method: 'GET', transformResponse: ModelMapperService.taskGroups.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.taskGroups.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.taskGroups.formatRequest, transformResponse: ModelMapperService.taskGroups.parseResponse }
	});

	return {
		getContentTask: function (accountId, contentId, id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentTasks.get({ accountId: accountId, contentId: contentId, id: id }, success, error);
		},
		queryContentTasks: function (accountId, contentId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentTasks.query({ accountId: accountId, contentId: contentId }, success, error);
		},
		saveContentTasks: function(accountId, taskGroup, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentTasks.update({ accountId: accountId, contentId: taskGroup.contentId, id: taskGroup.id }, taskGroup, success, error);
		}
	};
});
