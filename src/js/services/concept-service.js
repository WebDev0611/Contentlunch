launch.module.factory('ConceptService', function($resource, ModelMapperService) {
	var conceptResource = $resource('/api/account/:accountId/concept/:id', { accountId: '@accountId', id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.concept.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.concept.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.concept.formatRequest, transformResponse: ModelMapperService.concept.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.concept.formatRequest, transformResponse: ModelMapperService.concept.parseResponse },
		delete: { method: 'DELETE' }
	});

//TODO: GET RID OF THIS AND POPULATE CONCEPTS FROM API!!
	var concepts = $.map([
		{ id: 1, title: 'Audio Concept', campaign: { id: 0, index: 1 }, contentType: 'audio-recording', author: { id: 14, name: 'Pam Beasley', image: 'url(\'http://local.contentlaunch.com/packages/andrew13/cabinet/uploads/2014/05/01/GOPR0013%20(640x480).jpg\')' }, description: 'Sample audio recordig description', collaborators: [], concept_type: 'content' },
		{ id: 2, title: 'Blog Post Concept', campaign: { id: 0, index: 15 }, contentType: 'blog-post', author: { id: 15, name: 'Jim Halpert', image: null }, description: 'Sample blog post description', collaborators: [], concept_type: 'content' },
		{ id: 3, title: 'Case Study Concept', campaign: { id: 0, index: 2 }, contentType: 'case-study', author: { id: 16, name: 'Dwight Schrute', image: null }, description: 'Sample case study description', collaborators: [], concept_type: 'content' },
		{ id: 4, title: 'eBook Concept', campaign: { id: 0, index: 14 }, contentType: 'ebook', author: { id: 12, name: 'Site Admin', image: null }, description: 'Sample ebook description', collaborators: [], concept_type: 'content' },
		{ id: 5, title: 'eMail Concept', campaign: { id: 0, index: 3 }, contentType: 'email', author: { id: 13, name: 'Michael Scott', image: null }, description: 'Sample email description', collaborators: [], concept_type: 'content' },
		{ id: 6, title: 'Facebook Post Concept', campaign: { id: 0, index: 13 }, contentType: 'facebook-post', author: { id: 14, name: 'Pam Beasley', image: 'url(\'http://local.contentlaunch.com/packages/andrew13/cabinet/uploads/2014/05/01/GOPR0013%20(640x480).jpg\')' }, description: 'Sample Facebook post description', collaborators: [], concept_type: 'content' },
		{ id: 7, title: 'Google Drive Concept', campaign: { id: 0, index: 4 }, contentType: 'google-drive', author: { id: 15, name: 'Jim Halpert', image: null }, description: 'Sample  description', collaborators: [], concept_type: 'content' },
		{ id: 8, title: 'Landing Page Concept', campaign: { id: 0, index: 12 }, contentType: 'landing-page', author: { id: 16, name: 'Dwight Schrute', image: null }, description: 'Sample landing page description.', collaborators: [], concept_type: 'content' },
		{ id: 9, title: 'LinkedIn Concept', campaign: { id: 0, index: 5 }, contentType: 'linkedin-update', author: { id: 12, name: 'Site Admin', image: null }, description: 'Sample LinkedIn description.', collaborators: [], concept_type: 'content' },
		{ id: 10, title: 'Photo Concept', campaign: { id: 0, index: 11 }, contentType: 'photo', author: { id: 13, name: 'Michael Scott', image: null }, description: 'Sample photo description.', collaborators: [], concept_type: 'content' },
		{ id: 11, title: 'Salesforce Asset Concept', campaign: { id: 0, index: 6 }, contentType: 'salesforce-asset', author: { id: 14, name: 'Pam Beasley', image: 'url(\'http://local.contentlaunch.com/packages/andrew13/cabinet/uploads/2014/05/01/GOPR0013%20(640x480).jpg\')' }, description: 'Sample Salesforce asset description.', collaborators: [], concept_type: 'content' },
		{ id: 12, title: 'Twitter Concept', campaign: { id: 0, index: 10 }, contentType: 'tweet', author: { id: 15, name: 'Jim Halpert', image: null }, description: 'Sample Twitter description.', collaborators: [], concept_type: 'content' },
		{ id: 13, title: 'Video Concept', campaign: { id: 0, index: 7 }, contentType: 'video', author: { id: 16, name: 'Dwight Schrute', image: null }, description: 'Sample video description.', collaborators: [], concept_type: 'content' },
		{ id: 14, title: 'Whitepaper Concept', campaign: { id: 0, index: 9 }, contentType: 'whitepaper', author: { id: 12, name: 'Site Admin', image: null }, description: 'Sample whitepaper description.', collaborators: [], concept_type: 'content' }
	], function(c, i) {
		return ModelMapperService.concept.fromDto(c);
	});

	return {
		query: function(accountId, params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			//return conceptResource.query({ accountId: accountId }, success, error);

			if (!!success) {
				window.setTimeout(function() { success(); }, 100);
			}

			//TODO: POPULATE CONCEPT FROM API!!
			return concepts;
		},
		get: function(accountId, id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			//return conceptResource.get({ accountId: accountId, id: concept.id }, success, error);

			if (!!success) {
				window.setTimeout(function() { success(); }, 100);
			}

			//TODO: POPULATE CONCEPT FROM API!!
			var concept = $.grep(concepts, function(c) {
				return c.id === id;
			});

			return (concept.length === 1) ? concept[0] : null;
		},
		update: function (accountId, concept, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return conceptResource.update({ accountId: accountId, id: concept.id }, concept, success, error);
		},
		add: function (accountId, concept, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return conceptResource.insert({ accountId: accountId }, success, error);
		},
		delete: function (accountId, concept, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return conceptResource.delete({ accountId: accountId, id: concept.id }, success, error);
		},
		getNewContentConcept: function (user) {
			var concept = new launch.Concept();

			concept.conceptType = 'concept';
			concept.creator = {
				name: user.displayName,
				image: user.imageUrl()
			}
			concept.collaborators = [];

			return concept;
		},
		getNewCampaignConcept: function (user) {
			var concept = new launch.Concept();

			concept.conceptType = 'campaign';
			concept.creator = {
				name: user.displayName,
				image: user.imageUrl()
			}

			return concept;
		}
	};
});