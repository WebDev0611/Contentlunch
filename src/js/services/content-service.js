﻿launch.module.factory('ContentService', function ($resource, ModelMapperService, SessionService) {
	var contentResource = $resource('/api/account/:accountId/content/:id', { accountId: '@accountId', id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.content.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.content.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.content.formatRequest, transformResponse: ModelMapperService.content.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.content.formatRequest, transformResponse: ModelMapperService.content.parseResponse },
		delete: { method: 'DELETE' }
	});

	var contentType = $resource('/api/content-types', null, {
		get: { method: 'GET', isArray: true, transformResponse: ModelMapperService.contentType.parseResponse }
	});

	return {
		query: function(accountId, params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentResource.query({ accountId: accountId }, success, error);
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
		getContentTypes: function (callback, forceRefresh) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;
			var contentTypes = null;

			if (!forceRefresh) {
				contentTypes = SessionService.get(SessionService.CONTENT_TYPES_KEY);

				if (!launch.utils.isBlank(contentTypes)) {
					return JSON.parse(contentTypes);
				}
			}

			contentTypes = contentType.get(null, function (r) {
				SessionService.set(SessionService.CONTENT_TYPES_KEY, JSON.stringify(contentTypes));
				success(r);
			}, error);

			return contentTypes;
		},
		getNewContentConcept: function (user) {
			var content = new launch.Content();

			content.accountId = user.account.id;
			content.author = user;
			content.status = 0;
			content.collaborators = [];
			content.comments = [];
			content.accountConnections = [];
			content.contentType = { };

			return content;
		}
	};
});