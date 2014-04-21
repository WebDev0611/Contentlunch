﻿launch.module.factory('ConnectionService', function ($resource, ModelMapperService) {
	var contentConnections = $resource('/api/account/{accountId}/connections/{id}?type=content', { accountId: '@accountId', id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.contentConnection.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.contentConnection.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.contentConnection.formatRequest, transformResponse: ModelMapperService.contentConnection.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.contentConnection.formatRequest, transformResponse: ModelMapperService.contentConnection.parseResponse },
		delete: { method: 'DELETE' }
	});

	//var seoConnections = $resource('/api/account/{accountId}/connections/{id}?type=seo', { accountId: '@accountId', id: '@id' }, {
	//	get: { method: 'GET', transformResponse: ModelMapperService.seoConnection.parseResponse },
	//	query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.seoConnection.parseResponse }
	//});

	return {
		queryContentConnections: function (accountId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			// TODO: LOAD THE CONNECTIONS FROM THE API!!
			//contentConnections.query({ accountId: accountId }, success, error);

			var facebook = new launch.ContentConnection();
			var twitter = new launch.ContentConnection();
			var google = new launch.ContentConnection();
			var blogspot = new launch.ContentConnection();
			var linkedin = new launch.ContentConnection();
			var contentConnections = [];

			facebook.id = 1;
			facebook.name = 'Some Facebook Account';
			facebook.url = 'http://www.facebook.com/';
			facebook.connectionType = 'facebook';
			facebook.created = new Date();
			facebook.updated = new Date();

			twitter.id = 2;
			twitter.name = 'Some Twitter Account';
			twitter.url = 'http://www.twitter.com/';
			twitter.connectionType = 'twitter';
			twitter.created = new Date();
			twitter.updated = new Date();

			google.id = 3;
			google.name = 'Some Google+ Account';
			google.url = 'http://plus.google.com/';
			google.connectionType = 'google-plus';
			google.created = new Date();
			google.updated = new Date();

			blogspot.id = 4;
			blogspot.name = 'Some Blogspot Account';
			blogspot.url = 'http://www.blogspot.com/';
			blogspot.connectionType = 'blogspot';
			blogspot.created = new Date();
			blogspot.updated = new Date();

			linkedin.id = 1;
			linkedin.name = 'Some LinkedIn Account';
			linkedin.url = 'http://www.linkedin.com/';
			linkedin.connectionType = 'linkedin';
			linkedin.created = new Date();
			linkedin.updated = new Date();

			contentConnections.push(facebook);
			contentConnections.push(twitter);
			contentConnections.push(google);
			contentConnections.push(blogspot);
			contentConnections.push(linkedin);

			contentConnections.sort(ModelMapperService.contentConnection.sort);

			return contentConnections;
		},
		getContentConnection: function (accountId, id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			contentConnections.get({ accountId: accountId, id: id }, success, error);
		},
		updateContentConnection: function (connection, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			contentConnections.update({ accountId: accountId, id: id }, connection, success, error);
		},
		addContentConnection: function (connection, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			contentConnections.insert({ accountId: accountId }, connection, success, error);
		},
		deleteContentConnection: function (connection, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			contentConnections.delete({ accountId: accountId, id: id }, connection, success, error);
		},
		//querySeoConnections: function (accountId, callback) {
		//	var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
		//	var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

		//	seoConnections.query({ accountId: accountId }, success, error);
		//},
		//getSeoConnection: function (accountId, id, callback) {
		//	var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
		//	var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

		//	seoConnections.get({ accountId: accountId, id: id }, success, error);
		//}
	};
});