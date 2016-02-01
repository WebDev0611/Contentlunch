launch.module.factory('ContentSettingsService', function ($resource, ModelMapperService) {
	var contentSettings = $resource('/api/account/:accountId/content-settings', { accountId: '@accountId' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.contentSettings.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.contentSettings.formatRequest, transformResponse: ModelMapperService.contentSettings.parseResponse }
	});

	return {
		get: function (accountId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentSettings.get({ accountId: accountId }, success, error);
		},
		update: function (settings, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return contentSettings.update({ accountId: settings.accountId }, settings, success, error);
		}
	};
});