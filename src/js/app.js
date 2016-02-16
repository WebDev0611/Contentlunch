(function(window, angular) {
	'use strict';

	var launch = window.launch || (window.launch = {});
	launch.module = angular.module('launch', [
		'ngResource',
		'ngSanitize',
		'ui.bootstrap',
		'angularFileUpload',
		'ui.tinymce',
		'ui.select2',
		'ui.router',
		'restangular',
		'checklist-model',
		'wijmo']);

	launch.module.value('contentStatuses', ['concept',
		'create',
		'review',
		'launch',
		'promote']);

	launch.module.value('ecommercePlatforms', [
		{id: 'magento', name: 'Magento'},
		{id: 'volusion', name: 'Volusion'},
		{id: 'shopify', name: 'Shopify'},
		{id: 'woo-commerce', name: 'Woo Commerce'},
		{id: 'big-commerce', name: 'Big Commerce'},
		{id: 'other', name: 'Other'}
	]);

	launch.module.config([
			'$httpProvider', 'RestangularProvider',
			function ($httpProvider, RestangularProvider) {


				RestangularProvider.setBaseUrl('/api');

				// take all the requests from the server and transform snake_case to camelCase
				RestangularProvider.addResponseInterceptor(function (data, operation, route, url) {
					if (_.isArray(data)) return _.map(data, toClient);
					return toClient(data);
				});

				// take all the requests to the server (that have data) and convert snake_case back to camelCase
				RestangularProvider.addRequestInterceptor(function (data, operation, route, url) {
					operation = operation.toUpperCase();
					if (operation === 'GET' || operation === 'GETLIST' || operation === 'REMOVE' || operation === 'DELETE')
						return data;

					var origData = angular.copy(data);

					try {
						data = toServer(data);
						return data;
					} catch (err) {
						console.error(err);
						return origData;
					}
				});

				// thank https://github.com/blakeembrey/change-case/
				// for help on the case changing here
				function toClient(data) {
					if (!angular.isObject(data) && !angular.isArray(data))
						return data;

					// name shortcut
					if (data.first_name && data.last_name)
						data.name = data.first_name + ' ' + data.last_name;

					return _.mapObject(data, function (value, key) {
						// camelCaseize keys
						key = (key + '').replace(/_(\w)/g, function (_, $1) {
							return $1.toUpperCase();
						});

						// adjust dates for local timezone
						if (isDateString(value)) {
							// console.debug(key, 'before local:', value);
							value = moment.utc(value).local().format();
							// console.debug(key, 'after local:', value);
						}

						if (_.isArray(value)) value = _.map(value, toClient);
						else if (_.isPlainObject(value)) value = toClient(value);
						return [key, value];
					});
				}

				function toServer(data) {
					if (!angular.isObject(data) && !angular.isArray(data))
						return data;

					return _.mapObject(data, function (value, key) {
						// adjust dates for UTC timezone
						if (isDateString(value)) {
							// console.debug(key, 'before utc:', value);
							value = moment(value).utc().format('YYYY-MM-DD HH:mm:ss');
							// console.debug(key, 'after utc:', value);
						}

						// snake_caseize keys
						key = (key + '').replace(/([a-z])([A-Z0-9])/g, function (_, $1, $2) {
							return $1 + '_' + $2.toLowerCase();
						});

						if (_.isArray(value)) value = _.map(value, toServer);
						else if (_.isPlainObject(value)) value = toServer(value);
						return [key, value];
					});
				}

				function isDateString(str) {
					// disable for now
					return false;

					if (!_.isString(str)) return false;

					return str.match(/^\d{4}-\d{2}-\d{2}(?:[T ]\d{2}:\d{2}:\d{2})?(?:[-+]\d{2}:00)?$/);
				}

				var interceptor = [
					'$location', '$q', function ($location, $q) {
						var success = function (r) {
							return r;
						};

						var error = function (r) {
							if (r.status === 401) {
								if (!launch.utils.startsWith($location.path(), '/user/confirm/')) {
									// TODO: OPEN DIALOG HERE!!
									$location.path('/login');
								}

								return $q.reject(r);
							} else {
								return $q.reject(r);
							}
						};

						return function (promise) {
							return promise.then(success, error);
						};
					}
				];

				$httpProvider.responseInterceptors.push(interceptor);

				$httpProvider.defaults.headers.common['X-Timezone'] = moment().format('Z');
			}
		])
		.run(['$rootScope', '$location', 'UserService', 'AuthService', 'NotificationService',
			function ($rootScope, $location, userService, authService, notificationService) {
				$rootScope.yes = true;
				$rootScope.no = false;

				var path = $location.path();

				var fetchCurrentUser = function (r) {
					if (!r.id && $location.path() !== '/login' && $location.path().indexOf('/user/confirm') !== 0) {
						console.log('redirecting to login');
						$location.path('/login').search('path', path);
					}
				};

				//$rootScope.$on('$routeChangeStart', function (event, next, current) {
				//	if ($location.path() === '/login') {
				//		authService.logout();
				//	} else if (!next.allowAnon && !authService.isLoggedIn()) {
				//		// if you want a page to be allowed access anonymously,
				//		// set the flag "allowAnon" to true in the route defintion
				//		//authService.fetchCurrentUser({
				//		//	success: fetchCurrentUser
				//		//});
				//		$location.path('/login');
				//		event.preventDefault();
				//	}
				//});

				$rootScope.$on('$routeChangeSuccess', function (event, next, current) {
					// Bootstrap popovers aren't going away causing issues
					$('.popover').remove();
				});

				$.pnotify.defaults.styling = "bootstrap3";
				$.pnotify.defaults.history = false;

				// Generic Template-wide helpers
				// -------------------------
				$rootScope.addRow = function (array, item) {
					array.push(item);
				};

				// byId is opt OUT
				$rootScope.removeRow = function (array, index, byId) {
					if (byId !== false) {
						index = _.indexById(array, index);
					}
					if (index !== -1) array.splice(index, 1);
				};

				$rootScope.globalErrorHandler = function (err) {
					var errorMessage = (err.data || {});
					errorMessage = errorMessage.errors || errorMessage.error;
					if (angular.isArray(errorMessage)) errorMessage = errorMessage.join('<br>');
					notificationService.error(errorMessage || err.data || err || 'Unknown Error.');
					console.error(err);
				};
			}
		]);

	launch.module.filter('pageStart', function () {
		return function (input, start) {
			return input.splice(parseInt(start));
		};
	});

	// handlebars/angular style interpolation: {{ name }}
	_.templateSettings.interpolate = /\{\{ +(.+?) +\}\}/g;
	_.mixin({
		mapObject: _.compose(_.object, _.map),
		findById: function (items, id) {
			return _.find(items, function (item) {
				return item.id == id;
			});
		},
		appendOrUpdate: function (array, item) {
			var index = _.indexById(array, item.id);

			if (index !== -1) array[index] = angular.copy(item);
			else array.push(angular.copy(item));
		},
		remove: function (array, item) {
			if (!item.id) item = {id: item};
			var index = _.indexById(array, item.id);
			if (index !== -1) array.splice(index, 1);
		},
		stripTags: function (str) {
			if (!str) return '';
			return ('' + str).replace(/<\/?[^>]+>/g, '');
		},
		indexById: function (array, id) {
			var index = -1;

			// we could use 2 underscore functions to do this, but
			// then it would have to loop through everything twice
			var exists = _.any(array, function (item) {
				index++;
				return item.id == id;
			});

			return exists ? index : -1;
		}
	});

})(window, angular);
