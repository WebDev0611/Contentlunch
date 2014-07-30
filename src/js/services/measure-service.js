launch.module.factory('MeasureService', function ($resource, $upload, ModelMapperService) {
	var overview = $resource('/api/account/:accountId/measure/overview', { accountId: '@accountId' }, {
		get: { method: 'GET' }
	});

	var created = $resource('/api/account/:accountId/measure/content-created', { accountId: '@accountId' }, {
		get: { method: 'GET' }
	});

	var launched = $resource('/api/account/:accountId/measure/content-launched', { accountId: '@accountId' }, {
		get: { method: 'GET' }
	});

	var timing = $resource('/api/account/:accountId/measure/content-timing', { accountId: '@accountId' }, {
		get: { method: 'GET' }
	});

	var eficiency = $resource('/api/account/:accountId/measure/content-efficiency', { accountId: '@accountId' }, {
		get: { method: 'GET' }
	});

	return {
		getOverview: function (accountId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			//return overview.get({ accountId: accountId }, success, error);

			return {
				companyScore: parseFloat(Math.random() * 100).toFixed(2),
				totalContent: parseInt(Math.random() * 10000),
				productionDays: parseInt(Math.random() * 500),
				totalContentScore: parseFloat(Math.random() * 1000).toFixed(2),
				averageContentScore: parseFloat(Math.random() * 100).toFixed(2)
			};
		},
		getCreated: function (accountId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return created.get({ accountId: accountId }, success, error);
		},
		getLaunched: function (accountId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return launched.get({ accountId: accountId }, success, error);
		},
		getTiming: function (accountId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return timing.get({ accountId: accountId }, success, error);
		},
		getEfficiency: function (accountId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return eficiency.get({ accountId: accountId }, success, error);
		}
	};
});