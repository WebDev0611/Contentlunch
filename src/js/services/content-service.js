launch.module.factory('ContentService', function($resource, ModelMapperService) {
	var contentResource = $resource('/api/content/:id', { id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.content.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.content.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.content.formatRequest, transformResponse: ModelMapperService.content.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.content.formatRequest, transformResponse: ModelMapperService.content.parseResponse },
		delete: { method: 'DELETE' }
	});

	return {
		query: function(accountId, params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			//return contentResource.query({ accountId: accountId }, success, error);

			//TODO: POPULATE CONTENT FROM API!!
			return $.map([
				{ title: 'Sample Audio', contentType: 'audio', author: { id: 2, name: 'Test	site_admin' }, persona: 'CMO', buyingStage: 1, currentStep: '', nextStep: '' },
				{ title: 'Sample Blog Post', contentType: 'blog_post', author: { id: 3, name: 'Test	creator' }, persona: 'VP Sales', buyingStage: 2, currentStep: '', nextStep: '' },
				{ title: 'Sample Case Study', contentType: 'case_study', author: { id: 4, name: 'Test	manager' }, persona: 'Sales Rep', buyingStage: 3, currentStep: '', nextStep: '' },
				{ title: 'Sample eBook', contentType: 'ebook', author: { id: 5, name: 'Test	editor' }, persona: 'Product Manager', buyingStage: 4, currentStep: '', nextStep: '' },
				{ title: 'Sample eMail', contentType: 'email', author: { id: 6, name: 'Test	client' }, persona: 'CMO', buyingStage: 5, currentStep: '', nextStep: '' },
				{ title: 'Sample Facebook Post', contentType: 'facebook_post', author: { id: 2, name: 'Test	site_admin' }, persona: 'VP Sales', buyingStage: 1, currentStep: '', nextStep: '' },
				{ title: 'Sample Google Drive', contentType: 'google_drive', author: { id: 3, name: 'Test	creator' }, persona: 'Sales Rep', buyingStage: 2, currentStep: '', nextStep: '' },
				{ title: 'Sample Landing Page', contentType: 'landing_page', author: { id: 4, name: 'Test	manager' }, persona: 'Product Manager', buyingStage: 3, currentStep: '', nextStep: '' },
				{ title: 'Sample LinkedIn', contentType: 'linkedin', author: { id: 5, name: 'Test	editor' }, persona: 'CMO', buyingStage: 4, currentStep: '', nextStep: '' },
				{ title: 'Sample Photo', contentType: 'photo', author: { id: 6, name: 'Test	client' }, persona: 'VP Sales', buyingStage: 5, currentStep: '', nextStep: '' },
				{ title: 'Sample Salesforce Asset', contentType: 'salesforce_asset', author: { id: 2, name: 'Test	site_admin' }, persona: 'Sales Rep', buyingStage: 1, currentStep: '', nextStep: '' },
				{ title: 'Sample Twitter', contentType: 'twitter', author: { id: 3, name: 'Test	creator' }, persona: 'Product Manager', buyingStage: 2, currentStep: '', nextStep: '' },
				{ title: 'Sample Video', contentType: 'video', author: { id: 4, name: 'Test	manager' }, persona: 'CMO', buyingStage: 3, currentStep: '', nextStep: '' },
				{ title: 'Sample Whitepaper', contentType: 'whitepaper', author: { id: 5, name: 'Test	editor' }, persona: 'VP Sales', buyingStage: 4, currentStep: '', nextStep: '' }
			], function(c, i) {
				return ModelMapperService.content.fromDto(c);
			});

		},
		get: function (accountId, id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentResource.get({ accountId: accountId, id: content.id }, success, error);
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
		}
	};
});