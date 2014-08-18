launch.module.factory('MeasureService', function ($resource, $upload, ModelMapperService) {
	var overview = $resource('/api/account/:accountId/measure/overview', { accountId: '@accountId' }, {
		get: { method: 'GET' }
	});

	var created = $resource('/api/account/:accountId/measure/content-created', { accountId: '@accountId'}, {
		get: { method: 'GET', isArray: true, transformResponse: ModelMapperService.measure.parseResponse }
	});

	var launched = $resource('/api/account/:accountId/measure/content-launched', { accountId: '@accountId' }, {
		get: { method: 'GET', isArray: true, transformResponse: ModelMapperService.measure.parseResponse }
	});

	var timing = $resource('/api/account/:accountId/measure/content-timing', { accountId: '@accountId' }, {
		get: { method: 'GET', isArray: true, transformResponse: ModelMapperService.measure.parseResponse }
	});

    var score = $resource('/api/account/:accountId/measure/content-score', { accountId: '@accountId' }, {
        get: { method: 'GET', isArray: true, transformResponse: ModelMapperService.measure.parseResponse }
    });

	var efficiency = $resource('/api/account/:accountId/measure/user-efficiency', { accountId: '@accountId' }, {
		get: { method: 'GET', isArray: true, transformResponse: ModelMapperService.measure.parseResponse }
	});

	var automation = $resource('/api/account/:accountId/measure/automation', { accountId: '@accountId' }, {
		get: { method: 'GET', isArray: true, transformResponse: ModelMapperService.content.parseResponse }
	});

	return {
		getOverview: function (accountId, startDate, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			//return overview.get({ accountId: accountId }, success, error);
            startDate = moment(startDate);

            var data = [];
            while(startDate < moment()) {
                data.push({
                    date: startDate.format('YYYY-MM-DD'),
                    stats: {
                        companyScore: parseFloat(Math.random() * 100).toFixed(2),
                        totalContent: parseInt(Math.random() * 10000),
                        productionDays: parseInt(Math.random() * 500),
                        totalContentScore: parseFloat(Math.random() * 1000).toFixed(2),
                        averageContentScore: parseFloat(Math.random() * 100).toFixed(2)
                    }
                });
                startDate.add(1, 'days');
            }

            return data;
		},
		getCreated: function (accountId, startDate, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return created.get({ accountId: accountId, start_date: startDate }, success, error);
		},
		getLaunched: function (accountId, startDate, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return launched.get({ accountId: accountId, start_date: startDate }, success, error);
		},
        getTiming: function (accountId, startDate, callback) {
            var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
            var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

            return timing.get({ accountId: accountId, start_date: startDate }, success, error);
        },
        getScore: function (accountId, startDate, callback) {
            var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
            var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

            return score.get({ accountId: accountId, start_date: startDate }, success, error);
        },
		getEfficiency: function (accountId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return efficiency.get({ accountId: accountId }, success, error);
		},
		getAutomation: function (accountId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return automation.get({ accountId: accountId }, success, error);
		}
	};
});