﻿launch.module.factory('ContentService', function ($resource, ModelMapperService, SessionService) {
	var contentResource = $resource('/api/account/:accountId/content/:id', { accountId: '@accountId', id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.content.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.content.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.content.formatRequest, transformResponse: ModelMapperService.content.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.content.formatRequest, transformResponse: ModelMapperService.content.parseResponse },
		delete: { method: 'DELETE' }
	});

	var contentComments = $resource('/api/account/:accountId/content/:contentId/comments', { accountId: '@accountId', id: '@contentId' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.comment.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.comment.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.comment.formatRequest, transformResponse: ModelMapperService.comment.parseResponse }
	});

	var contentCollaborators = $resource('/api/account/:accountId/content/:contentId/collaborators/:userId', { accountId: '@accountId', contentId: '@contentId', userId: '@userId' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.user.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.user.parseResponse },
		insert: { method: 'POST', isArray: true, transformResponse: ModelMapperService.user.parseResponse },
		delete: { method: 'DELETE', isArray: true, transformResponse: ModelMapperService.user.parseResponse }
	});

	var contentGuestCollaborators = $resource('/api/account/:accountId/content/:contentId/guest-collaborators', { accountId: '@accountId', contentId: '@contentId' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.guestCollaborator.parseResponse }
	});

	var contentTaskGroups = $resource('/api/account/:accountId/content/:contentId/task-group', { accountId: '@accountId', contentId: '@contentId' }, {
		get: { method: 'GET', isArray: true, transformResponse: ModelMapperService.taskGroups.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.taskGroups.formatRequest, transformResponse: ModelMapperService.taskGroups.parseResponse }
	});

	var contentActivity = $resource('/api/account/:accountId/content/:contentId/activity', { accountId: '@accountId', contentId: '@contentId' }, {
		get: { method: 'GET', isArray: true, transformResponse: ModelMapperService.contentActivity.parseResponse }
	});

	var contentType = $resource('/api/content-types', null, {
		get: { method: 'GET', isArray: true, transformResponse: ModelMapperService.contentType.parseResponse }
	});

	var contentLaunch = $resource('/api/account/:accountId/content/:contentId/launch/:accountConnectionId', { accountId: '@accountId', contentId: '@contentId', accountConnectionId: '@accountConnectionId' }, {
		get: { method: 'GET', isArray: true, transformResponse: ModelMapperService.launchedContent.parseResponse },
		post: { method: 'POST' }
	});

    var contentAnalyze = $resource('/api/account/:accountId/content/:contentId/analyze', { accountId: '@accountId', contentId: '@contentId' }, {
        post: {method: 'POST'}
    });

	return {
		query: function(accountId, params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentResource.query(_.merge({ accountId: accountId }, params), success, error);
		},
		get: function (accountId, id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentResource.get({ accountId: accountId, id: id }, success, error);
		},
		update: function (accountId, content, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentResource.update({ accountId: accountId, id: content.id }, content, success, error);
		},
		add: function (accountId, content, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentResource.insert({ accountId: accountId }, content, success, error);
		},
		delete: function (accountId, content, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentResource.delete({ accountId: accountId, id: content.id }, success, error);
		},
		launch: function (accountId, contentId, accountConnectionId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentLaunch.post({ accountId: accountId, contentId: contentId, accountConnectionId: accountConnectionId }, null, success, error);
		},
		getLaunches: function(accountId, contentId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			// TODO: UNCOMMENT THIS WHEN IT'S AVAILABLE IN THE API!!
			//return contentLaunch.get({ accountId: accountId, contentId: contentId }, success, error);

			return [];
		},
		getComment: function (accountId, contentId, id, params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentComments.get({ accountId: accountId, contentId: contentId, id: id }, success, error);
		},
		queryComments: function (accountId, contentId, params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentComments.query({ accountId: accountId, contentId: contentId }, success, error);
		},
		insertComment: function(accountId, comment, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentComments.insert({ accountId: accountId, contentId: comment.itemId }, comment, success, error);
		},
		getCollaborator: function (accountId, contentId, userId, params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentCollaborators.get({ accountId: accountId, contentId: contentId, userId: userId }, success, error);
		},
		queryCollaborators: function (accountId, contentId, params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentCollaborators.query({ accountId: accountId, contentId: contentId }, success, error);
		},
		insertCollaborator: function (accountId, contentId, userId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentCollaborators.insert({ accountId: accountId, contentId: contentId }, { user_id: userId }, success, error);
		},
		deleteCollaborator: function (accountId, contentId, userId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentCollaborators.delete({ accountId: accountId, contentId: contentId, userId: userId }, success, error);
		},
		queryGuestCollaborators: function(accountId, contentId, params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentGuestCollaborators.query({ accountId: accountId, contentId: contentId }, success, error);
		},
		getTaskGroups: function (accountId, contentId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentTaskGroups.get({ accountId: accountId, contentId: contentId }, success, error);
		},
		updateTaskGroups: function (accountId, contentId, taskGroups, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentTaskGroups.update({ accountId: accountId, contentId: contentId }, taskGroups, success, error);
		},
		getActivity: function(accountId, contentId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentActivity.get({ accountId: accountId, contentId: contentId }, success, error);
		},
		getContentTypes: function (callback, forceRefresh) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;
			var contentTypes = null;

			if (!forceRefresh) {
				contentTypes = SessionService.get(SessionService.CONTENT_TYPES_KEY);

				if (!launch.utils.isBlank(contentTypes)) {
					return $.map(JSON.parse(contentTypes), ModelMapperService.contentType.fromCache);
				}
			}

			contentTypes = contentType.get(null, function (r) {
				SessionService.set(SessionService.CONTENT_TYPES_KEY, JSON.stringify(contentTypes));
				success(r);
			}, error);

			return contentTypes;
		},
		getNewContent: function(user) {
			var content = new launch.Content();

			content.accountId = user.account.id;
			content.author = user;
			content.status = 1;
			content.collaborators = [];
			content.comments = [];
			content.accountConnections = [];
			content.contentType = new launch.ContentType();
			content.$resolved = true;

			return content;
		},
		getNewContentConcept: function (user) {
			var content = new launch.Content();

			content.accountId = user.account.id;
			content.author = user;
			content.status = 0;
			content.collaborators = [];
			content.comments = [];
			content.accountConnections = [];
			content.contentType = {};
			content.$resolved = true;

			return content;
		},
        analyze: function(accountId, contentId, callback) {
            var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
            var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

            return contentAnalyze.post({ accountId: accountId, contentId: contentId }, success, error);
        },
	};
});