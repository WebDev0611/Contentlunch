launch.module.factory('MeasureService', function ($resource, $upload, ModelMapperService) {
	var overview = $resource('/api/account/:accountId/measure/overview', { accountId: '@accountId' }, {
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
		}
	};
});