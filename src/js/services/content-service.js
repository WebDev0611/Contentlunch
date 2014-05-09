launch.module.factory('ContentService', function ($resource, ModelMapperService, SessionService) {
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

	//TODO: GET RID OF THIS AND POPULATE CONTENT FROM API!!
	var contents = $.map([
				{ id: 1, title: 'Sample Audio', campaign: { id: 0, index: 1 }, contentType: 'audio-recording', author: { id: 14, name: 'Pam Beasley', image: 'url(\'http://local.contentlaunch.com/packages/andrew13/cabinet/uploads/2014/05/01/GOPR0013%20(640x480).jpg\')' }, persona: 'CMO', buyingStage: 0, currentStep: { name: 'concept', date: new Date() }, nextStep: { name: 'create', date: new Date() } },
				{ id: 2, title: 'Sample Blog Post', campaign: { id: 0, index: 15 }, contentType: 'blog-post', author: { id: 15, name: 'Jim Halpert', image: null }, persona: 'VP Sales', buyingStage: 1, currentStep: { name: 'create', date: new Date() }, nextStep: { name: 'edit', date: new Date() } },
				{ id: 3, title: 'Sample Case Study', campaign: { id: 0, index: 2 }, contentType: 'case-study', author: { id: 16, name: 'Dwight Schrute', image: null }, persona: 'Sales Rep', buyingStage: 2, currentStep: { name: 'edit', date: new Date() }, nextStep: { name: 'approve', date: new Date() } },
				{ id: 4, title: 'Sample eBook', campaign: { id: 0, index: 14 }, contentType: 'ebook', author: { id: 12, name: 'Site Admin', image: null }, persona: 'Product Manager', buyingStage: 3, currentStep: { name: 'approve', date: new Date() }, nextStep: { name: 'launch', date: new Date() } },
				{ id: 5, title: 'Sample eMail', campaign: { id: 0, index: 3 }, contentType: 'email', author: { id: 13, name: 'Michael Scott', image: null }, persona: 'CMO', buyingStage: 4, currentStep: { name: 'launch', date: new Date() }, nextStep: { name: 'promote', date: new Date() } },
				{ id: 6, title: 'Sample Facebook Post', campaign: { id: 0, index: 13 }, contentType: 'facebook-post', author: { id: 14, name: 'Pam Beasley', image: 'url(\'http://local.contentlaunch.com/packages/andrew13/cabinet/uploads/2014/05/01/GOPR0013%20(640x480).jpg\')' }, persona: 'VP Sales', buyingStage: 0, currentStep: { name: 'promote', date: new Date() }, nextStep: { name: 'archive', date: new Date() } },
				{ id: 7, title: 'Sample Google Drive', campaign: { id: 0, index: 4 }, contentType: 'google-drive', author: { id: 15, name: 'Jim Halpert', image: null }, persona: 'Sales Rep', buyingStage: 1, currentStep: { name: 'archive', date: new Date() }, nextStep: { name: 'restore', date: new Date() } },
				{ id: 8, title: 'Sample Landing Page', campaign: { id: 0, index: 12 }, contentType: 'landing-page', author: { id: 16, name: 'Dwight Schrute', image: null }, persona: 'Product Manager', buyingStage: 2, currentStep: { name: 'concept', date: new Date() }, nextStep: { name: 'create', date: new Date() }, description: 'Sample landing page description.' },
				{ id: 9, title: 'Sample LinkedIn', campaign: { id: 0, index: 5 }, contentType: 'linkedin-update', author: { id: 12, name: 'Site Admin', image: null }, persona: 'CMO', buyingStage: 3, currentStep: { name: 'create', date: new Date() }, nextStep: { name: 'edit', date: new Date() } },
				{ id: 10, title: 'Sample Photo', campaign: { id: 0, index: 11 }, contentType: 'photo', author: { id: 13, name: 'Michael Scott', image: null }, persona: 'VP Sales', buyingStage: 4, currentStep: { name: 'edit', date: new Date() }, nextStep: { name: 'approve', date: new Date() } },
				{ id: 11, title: 'Sample Salesforce Asset', campaign: { id: 0, index: 6 }, contentType: 'salesforce-asset', author: { id: 14, name: 'Pam Beasley', image: 'url(\'http://local.contentlaunch.com/packages/andrew13/cabinet/uploads/2014/05/01/GOPR0013%20(640x480).jpg\')' }, persona: 'Sales Rep', buyingStage: 0, currentStep: { name: 'approve', date: new Date() }, nextStep: { name: 'launch', date: new Date() } },
				{ id: 12, title: 'Sample Twitter', campaign: { id: 0, index: 10 }, contentType: 'tweet', author: { id: 15, name: 'Jim Halpert', image: null }, persona: 'Product Manager', buyingStage: 1, currentStep: { name: 'launch', date: new Date() }, nextStep: { name: 'promote', date: new Date() } },
				{ id: 13, title: 'Sample Video', campaign: { id: 0, index: 7 }, contentType: 'video', author: { id: 16, name: 'Dwight Schrute', image: null }, persona: 'CMO', buyingStage: 2, currentStep: { name: 'promote', date: new Date() }, nextStep: { name: 'archive', date: new Date() } },
				{ id: 14, title: 'Sample Whitepaper', campaign: { id: 0, index: 9 }, contentType: 'whitepaper', author: { id: 12, name: 'Site Admin', image: null }, persona: 'VP Sales', buyingStage: 3, currentStep: { name: 'archive', date: new Date() }, nextStep: { name: 'restore', date: new Date() } }
	], function (c, i) {
		return ModelMapperService.content.fromDto(c);
	});

	return {
		query: function(accountId, params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			//return contentResource.query({ accountId: accountId }, success, error);

			if (!!success) {
				window.setTimeout(function () { success(); }, 100);
			}

			//TODO: POPULATE CONTENT FROM API!!
			return contents;
		},
		get: function (accountId, id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			//return contentResource.get({ accountId: accountId, id: content.id }, success, error);

			if (!!success) {
				window.setTimeout(function () { success(); }, 100);
			}

			//TODO: POPULATE CONTENT FROM API!!
			var content = $.grep(contents, function(c) {
				return c.id === id;
			});

			return (content.length === 1) ? content[0] : null;
		},
		update: function (content, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentResource.update({ accountId: accountId, id: content.id }, content, success, error);
		},
		add: function (content, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentResource.insert({ accountId: accountId }, success, error);
		},
		delete: function (content, callback) {
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
		}
	};
});