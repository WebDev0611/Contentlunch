(function(window, angular) {
	'use strict';

	launch = window.launch || (window.launch = { });
	launch.module = angular.module('launch', ['ngRoute', 'ngResource', 'ngSanitize', 'ui.bootstrap', 'angularFileUpload', 'ui.tinymce', 'ui.select2', 'ui.calendar', 'restangular', 'checklist-model']);

	launch.module.config([
					'$routeProvider', '$locationProvider', '$httpProvider', 'RestangularProvider', 
			function($routeProvider,   $locationProvider,   $httpProvider,   RestangularProvider) {
				$locationProvider.html5Mode(true);

				$routeProvider
					.when('/', {
						controller: 'HomeController',
						templateUrl: '/assets/views/home.html'
					})
					.when('/login', {
						controller: 'LoginController',
						templateUrl: '/assets/views/login.html',
						allowAnon: true
					})
					.when('/account', {
						controller: 'AccountController',
						templateUrl: '/assets/views/account.html'
					})
					.when('/account/connections', {
						controller: 'ContentConnectionsController',
						templateUrl: '/assets/views/content-connections.html'
					})
					.when('/account/content-settings', {
						controller: 'ContentSettingsController',
						templateUrl: '/assets/views/content-settings.html'
					})
					.when('/account/seo', {
						controller: 'SeoSettingsController',
						templateUrl: '/assets/views/seo-settings.html'
					})
					.when('/user', {
						controller: 'UserController',
						templateUrl: '/assets/views/user.html'
					})
					.when('/user/confirm/:code', {
						controller: 'ConfirmController',
						templateUrl: '/assets/views/reset-password.html'
					})
					.when('/users', {
						controller: 'UsersController',
						templateUrl: '/assets/views/users.html'
					})
					.when('/roles', {
						controller: 'RolesController',
						templateUrl: '/assets/views/roles.html'
					})
					.when('/consult', {
						controller: 'ConsultController',
						templateUrl: '/assets/views/consult.html'
					})
					.when('/create', {
						controller: 'CreateController',
						templateUrl: '/assets/views/create.html'
					})
					.when('/create/concept/create/content', {
						controller: 'ContentConceptController',
						templateUrl: '/assets/views/content-concept.html'
					})
					.when('/create/concept/create/campaign', {
						controller: 'CampaignConceptController',
						templateUrl: '/assets/views/campaign-concept.html'
					})
					.when('/create/concept/edit/content/:contentId', {
						controller: 'ContentConceptController',
						templateUrl: '/assets/views/content-concept.html'
					})
					.when('/create/concept/edit/campaign/:campaignId', {
						controller: 'CampaignConceptController',
						templateUrl: '/assets/views/campaign-concept.html'
					})
					.when('/create/content/create', {
						controller: 'ContentController',
						templateUrl: '/assets/views/content-edit.html'
					})
					.when('/create/content/edit/:contentId', {
						controller: 'ContentController',
						templateUrl: '/assets/views/content-edit.html'
					})
					.when('/create/content/view/:contentId', {
						controller: 'ContentController',
						templateUrl: '/assets/views/content-view.html'
					})
					.when('/create/content/launch/:contentId', {
						controller: 'ContentController',
						templateUrl: '/assets/views/content-launch.html'
					})
					.when('/create/content/promote/:contentId', {
						controller: 'ContentController',
						templateUrl: '/assets/views/content-promote.html'
					})
					.when('/collaborate', {
						controller: 'CollaborateController',
						templateUrl: '/assets/views/collaborate/list.html'
					})
					.when('/collaborate/guest/:accessCode', {
						controller: 'GuestCollaboratorController',
						templateUrl: '/assets/views/collaborate/guest-landing.html',
						allowAnon: true
					})
					.when('/collaborate/guest/content/:contentId', {
						controller: 'GuestCampaignController',
						templateUrl: '/assets/views/collaborate/edit-content.html',
						allowAnon: true
					})
					.when('/collaborate/guest/campaign/:campaignId', {
						controller: 'GuestContentController',
						templateUrl: '/assets/views/collaborate/edit-campaign.html',
						allowAnon: true
					})
					.when('/collaborate/:conceptType/:id', {
						controller: 'CollaborateController',
						templateUrl: '/assets/views/collaborate/single.html'
					})
					.when('/calendar', {
						controller: 'CalendarController',
						templateUrl: '/assets/views/calendar.html'
					})
					.when('/calendar/campaigns/:campaignId', {
						controller: 'CampaignController',
						templateUrl: '/assets/views/calendar/campaign.html'
					})
					.when('/launch', {
						controller: 'LaunchController',
						templateUrl: '/assets/views/launch.html'
					})
					.when('/launch/content/:contentId', {
						controller: 'LaunchController',
						templateUrl: '/assets/views/launch.html'
					})
					.when('/launch/campaign/:campaignId', {
						controller: 'LaunchController',
						templateUrl: '/assets/views/launch.html'
					})
					.when('/measure', {
						controller: 'MeasureController',
						templateUrl: '/assets/views/measure.html'
					})
					.when('/accounts', {
						controller: 'AccountsController',
						templateUrl: '/assets/views/accounts.html'
					})
					.when('/subscription', {
						controller: 'SubscriptionController',
						templateUrl: '/assets/views/subscription.html'
					})
					.otherwise({
						redirectTo: function (params, path, search) {
							console.log('Invalid route: ' + path);

							return '/';
						}
				});

				RestangularProvider.setBaseUrl('/api');

				// take all the requests from the server and transform snake_case to camelCase
				RestangularProvider.addResponseInterceptor(function (data, operation, route, url) {
					if (_.isArray(data)) return _.map(data, camelCaseize);
					return camelCaseize(data);
				});

				// take all the requests to the server (that have data) and convert snake_case back to camelCase
				RestangularProvider.addRequestInterceptor(function (data, operation, route, url) {
					operation = operation.toUpperCase();
					if (operation === 'GET' || operation === 'GETLIST' || operation === 'REMOVE' || operation === 'DELETE') return data;
					var origData = angular.copy(origData);

					try {
						data = snakeCaseize(data);
						return data;
					} catch (err) {
						console.error(err);
						return origData;
					}
				});

				// thank https://github.com/blakeembrey/change-case/
				// for help on the case changing here
				function camelCaseize(data) {
					if (data.first_name && data.last_name)
						data.name = data.first_name + ' ' + data.last_name;

					return _.mapObject(data, function (value, key) {
						key = (key + '').replace(/_(\w)/g, function (_, $1) {
							return $1.toUpperCase();
						});
						if (_.isArray(value)) value = _.map(value, camelCaseize);
						else if (_.isPlainObject(value)) value = camelCaseize(value);
						return [key, value];
					});
				}
				function snakeCaseize(data) {
					return _.mapObject(data, function (value, key) {
						key = (key + '').replace(/([a-z])([A-Z0-9])/g, function (_, $1, $2) {
							return $1 + '_' + $2.toLowerCase();
						});
						if (_.isArray(value)) value = _.map(value, snakeCaseize);
						else if (_.isPlainObject(value)) value = snakeCaseize(value);
						return [key, value];
					});
				}

				var interceptor = [
					'$location', '$q', function($location, $q) {
						var success = function(r) {
							return r;
						}

						var error = function(r) {
							if (r.status === 401) {
								// TODO: OPEN DIALOG HERE!!
								$location.path('/login');
								return $q.reject(r);
							} else {
								return $q.reject(r);
							}
						}

						return function(promise) {
							return promise.then(success, error);
						}
					}
				];

				$httpProvider.responseInterceptors.push(interceptor);
			}
		])
		.run([
					'$rootScope', '$location', 'UserService', 'AuthService', 'NotificationService', 
			function($rootScope,   $location,   userService,   authService,   notificationService) {
				var path = $location.path();

				var fetchCurrentUser = function(r) {
					if (!r.id && $location.path() !== '/login') {
						$location.path('/login').search('path', path);
					}
				};

				$rootScope.$on('$routeChangeStart', function(event, next, current) {
					// TODO: VALIDATE THAT THE USER IS ALLOWED TO VIEW THE PAGE THEY ARE REQUESTING!! IF NOT, SHOW A WARNING OR ERROR AND REDIRECT TO HOME!!
					//          THIS MAY BE BETTER TO DO IN EACH CONTROLLER, HOWEVER?
					if ($location.path() === '/login') {
						authService.logout();
					} else if (!next.allowAnon && !authService.isLoggedIn()) {
						// if you want a page to be allowed access anonymously,
						// set the flag "allowAnon" to true in the route defintion
						authService.fetchCurrentUser({
							success: fetchCurrentUser
						});
					}
				});

				$.pnotify.defaults.styling = "bootstrap3";
				$.pnotify.defaults.history = false;

				// TODO: FIGURE OUT IF WE CAN SET THIS FROM THE SERVER!!!
				launch.config.DEBUG_MODE = true;

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

	launch.module.filter('pageStart', function() {
		return function(input, start) {
			return input.splice(parseInt(start));
		};
	});

	// handlebars/angular style interpolation: {{ name }}
	_.templateSettings.interpolate = /\{\{ +(.+?) +\}\}/g;
	_.mixin({
		mapObject: _.compose(_.object, _.map),
		findById: function(items, id) {
			return _.find(items, function (item) {
				return item.id == id;
			});
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
