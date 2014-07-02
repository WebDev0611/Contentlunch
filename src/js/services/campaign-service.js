launch.module.factory('CampaignService', function($resource, ModelMapperService) {
	var campaignResource = $resource('/api/account/:accountId/campaigns/:id', { accountId: '@accountId', id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.campaign.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.campaign.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.campaign.formatRequest, transformResponse: ModelMapperService.campaign.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.campaign.formatRequest, transformResponse: ModelMapperService.campaign.parseResponse },
		delete: { method: 'DELETE' }
	});

	var campaignComments = $resource('/api/account/:accountId/campaigns/:campaignId/comments', { accountId: '@accountId', id: '@campaignId' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.comment.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.comment.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.comment.formatRequest, transformResponse: ModelMapperService.comment.parseResponse }
	});

	var campaignCollaborators = $resource('/api/account/:accountId/campaigns/:campaignId/collaborators', { accountId: '@accountId', id: '@campaignId' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.user.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.user.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.user.formatRequest, transformResponse: ModelMapperService.user.parseResponse },
		delete: { method: 'DELETE' }
	});

	var campaignGuestCollaborators = $resource('/api/account/:accountId/campaign/:campaignId/guest-collaborators', { accountId: '@accountId', campaignId: '@campaignId' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.guestCollaborator.parseResponse }
	});

	return {
		query: function(accountId, params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return campaignResource.query({ accountId: accountId }, success, error);
		},
		get: function(accountId, id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return campaignResource.get({ accountId: accountId, id: id }, success, error);
		},
		update: function(accountId, campaign, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return campaignResource.update({ accountId: accountId, id: campaign.id }, campaign, success, error);
		},
		add: function(accountId, campaign, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return campaignResource.insert({ accountId: accountId }, campaign, success, error);
		},
		delete: function(accountId, campaign, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return campaignResource.delete({ accountId: accountId, id: campaign.id }, success, error);
		},
		getComment: function(accountId, campaignId, id, params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return campaignComments.get({ accountId: accountId, campaignId: campaignId, id: id }, success, error);
		},
		queryComments: function(accountId, campaignId, params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return campaignComments.query({ accountId: accountId, campaignId: campaignId }, success, error);
		},
		insertComment: function(accountId, comment, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return campaignComments.insert({ accountId: accountId, campaignId: comment.itemId }, comment, success, error);
		},
		getCollaborator: function(accountId, campaignId, id, params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return campaignCollaborators.get({ accountId: accountId, campaignId: campaignId, id: id }, success, error);
		},
		queryCollaborators: function(accountId, campaignId, params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return campaignCollaborators.query({ accountId: accountId, campaignId: campaignId }, success, error);
		},
		insertCollaborator: function(accountId, campaignId, collaborator, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return campaignCollaborators.insert({ accountId: accountId, campaignId: campaignId }, collaborator, success, error);
		},
		deleteCollaborator: function(accountId, campaignId, id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return campaignCollaborators.delete({ accountId: accountId, campaignId: campaignId, id: id }, success, error);
		},
		queryGuestCollaborators: function (accountId, campaignId, params, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return campaignGuestCollaborators.query({ accountId: accountId, campaignId: campaignId }, success, error);
		},
		getNewCampaignConcept: function (user) {
			var campaign = new launch.Campaign();

			campaign.accountId = user.account.id;
			campaign.user = user;
			campaign.status = 0;
			campaign.isActive = true;
			campaign.$resolved = true;

			return campaign;
		}
	};
});