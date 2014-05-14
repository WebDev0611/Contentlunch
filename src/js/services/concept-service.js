launch.module.factory('ConceptService', function($resource, ModelMapperService) {
	var conceptResource = $resource('/api/account/:accountId/concept/:id', { accountId: '@accountId', id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.concept.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.concept.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.concept.formatRequest, transformResponse: ModelMapperService.concept.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.concept.formatRequest, transformResponse: ModelMapperService.concept.parseResponse },
		delete: { method: 'DELETE' }
	});

	return {
		query: function(accountId, params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return conceptResource.query({ accountId: accountId }, success, error);
		},
		get: function(accountId, id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return conceptResource.get({ accountId: accountId, id: concept.id }, success, error);
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