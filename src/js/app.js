(function(window, angular) {
	'use strict';

	var launch = window.launch || (window.launch = {});
	launch.module = angular.module('launch', ['ngRoute',
		'ngResource',
		'ngSanitize',
		'ui.bootstrap',
		'angularFileUpload',
		'ui.tinymce',
		'ui.select2',
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
			'$routeProvider', '$locationProvider', '$httpProvider', 'RestangularProvider',
			function ($routeProvider, $locationProvider, $httpProvider, RestangularProvider) {
				$locationProvider.html5Mode(true);

				$routeProvider
					.when('/welcome', {
						controller: 'WelcomeController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/welcome.html'
					})
					.when('/', {
						controller: 'HomeController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/home.html',
						resolve: {
							app: function ($q, $rootScope, $location, AuthService) {
								var defer = $q.defer();
								var user = AuthService.userInfo();
								if (user && launch.utils.isBlank(user.account)) {
									$location.path('/accounts');
								}
								defer.resolve();
								return defer.promise;
							}
						}
					})
					.when('/agency', {
						controller: 'AgencyController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/agency.html',
						resolve: {
							userInfo: function (AuthService) {
								return AuthService.validateCurrentUser(); // this will make sure we're not using stale versions.
							}
						}
					})
					.when('/login', {
						controller: 'LoginController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/login.html',
						allowAnon: true
					})
					.when('/impersonate/reset', {
						controller: 'ResetImpersonateController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/home.html'
					})
					.when('/signup', {
						controller: 'SignupController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/account/signup.html',
						allowAnon: true
					})
					.when('/signup/confirm', {
						controller: 'SignupController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/account/signup_confirm.html',
						allowAnon: true
					})
					.when('/support', {
						controller: 'SupportController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/support.html'
					})
					.when('/account', {
						controller: 'AccountController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/account/account.html'
					})
					.when('/account/connections', {
						controller: 'ContentConnectionsController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/account/content-connections.html'
					})
					.when('/account/content-settings', {
						controller: 'ContentSettingsController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/account/content-settings.html'
					})
					//.when('/account/seo', {
					//	controller: 'SeoSettingsController',
					//	controllerAs: 'ctrl',
					//	templateUrl: '/assets/views/account/seo-settings.html'
					//})
					.when('/account/promote', {
						controller: 'PromoteConnectionsController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/account/promote-connections.html'
					})
					.when('/user', {
						controller: 'UserController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/user.html'
					})
					.when('/user/confirm/:code', {
						controller: 'ConfirmController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/reset-password.html',
						allowAnon: true
					})
					.when('/users', {
						controller: 'UsersController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/users.html'
					})
					.when('/roles', {
						controller: 'RolesController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/roles.html'
					})
					.when('/consult', {
						controller: 'ConsultController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/consult/consult-landing.html'
					})
					.when('/consult/admin-library', {
						controller: 'ConsultAdminLibraryController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/consult/admin-library.html'
					})
					.when('/consult/library', {
						controller: 'ConsultLibraryController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/consult/library.html'
					})
					.when('/consult/admin-conference', {
						controller: 'ConsultAdminConferenceController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/consult/admin-conference.html'
					})
					.when('/consult/conference', {
						controller: 'ConsultConferenceController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/consult/conference-list.html'
					})
					.when('/consult/forum', {
						controller: 'ForumController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/consult/forum/list.html'
					})
					.when('/consult/forum/:threadId', {
						controller: 'ForumThreadController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/consult/forum/thread.html'
					})
					.when('/consult/conference/:conferenceId', {
						controller: 'ConsultConferenceController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/consult/conference-view.html'
					})
					.when('/create', {
						controller: 'CreateController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/create.html'
					})
					.when('/create/concept/new/content', {
						controller: 'ContentConceptController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/content-concept.html'
					})
					.when('/calendar/concept/new/campaign', {
						controller: 'CampaignConceptController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/campaign-concept.html'
					})
					.when('/create/concept/edit/content/:contentId', {
						controller: 'ContentConceptController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/content-concept.html'
					})
					.when('/calendar/concept/edit/campaign/:campaignId', {
						controller: 'CampaignConceptController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/campaign-concept.html'
					})
					.when('/create/content/new', {
						controller: 'ContentController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/content-edit.html'
					})
					.when('/create/content/edit/:contentId', {
						controller: 'ContentController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/content-edit.html'
					})
					.when('/create/content/view/:contentId', {
						controller: 'ContentController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/content-view.html'
					})
					.when('/create/content/launch/:contentId', {
						controller: 'ContentController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/content-launch.html'
					})
					.when('/create/content/promote/:contentId', {
						controller: 'ContentController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/content-promote.html'
					})
					.when('/collaborate', {
						controller: 'CollaborateController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/collaborate/list.html'
					})
					.when('/collaborate/guest/:accessCode', {
						controller: 'GuestCollaboratorController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/collaborate/guest-landing.html',
						allowAnon: true
					})
					.when('/collaborate/guest/content/:contentId', {
						controller: 'GuestContentController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/collaborate/edit-concept.html',
						allowAnon: true
					})
					.when('/collaborate/guest/campaign/:campaignId', {
						controller: 'GuestCampaignController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/collaborate/edit-concept.html',
						allowAnon: true
					})
					.when('/collaborate/:conceptType/:id', {
						controller: 'CollaborateController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/collaborate/single.html'
					})
					.when('/calendar', {
						controller: 'CalendarController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/calendar.html'
					})
					.when('/calendar/campaigns/:campaignId', {
						controller: 'CampaignController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/calendar/campaign.html',
						reloadOnSearch: false
					})
					.when('/promote', {
						controller: 'CreateController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/promote/promote.html'
					})
					.when('/promote/content/new', {
						controller: 'ContentController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/promote/promote-content.html'
					})
					.when('/promote/content/:contentId', {
						controller: 'ContentController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/promote/promote-content.html'
					})
					.when('/promote/campaign/:campaignId', {
						controller: 'PromoteCampaignController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/promote/promote-campaign.html'
					})
					.when('/measure', {
						controller: 'MeasureOverviewController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/measure/measure-overview.html'
					})
					.when('/measure/creation-stats', {
						controller: 'MeasureCreationStatsController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/measure/measure-creation-stats.html'
					})
					.when('/measure/content-trends', {
						controller: 'MeasureContentTrendsController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/measure/measure-content-trends.html'
					})
					.when('/measure/content-details', {
						controller: 'MeasureContentDetailsController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/measure/measure-content-details.html'
					})
					.when('/measure/marketing-automation', {
						controller: 'MeasureMarketingAutomationController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/measure/measure-marketing-automation.html'
					})
					.when('/measure/content/:contentId', {
						controller: 'MeasureContentItemController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/measure/measure-content-item-details.html'
					})
					.when('/accounts', {
						controller: 'AccountsController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/account/accounts.html'
					})
					.when('/subscription', {
						controller: 'SubscriptionController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/subscription.html'
					})
					.when('/announce', {
						controller: 'AnnouncementsController',
						controllerAs: 'ctrl',
						templateUrl: '/assets/views/announcements.html'
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

				$rootScope.$on('$routeChangeStart', function (event, next, current) {
					if ($location.path() === '/login') {
						authService.logout();
					} else if (!next.allowAnon && !authService.isLoggedIn()) {
						// if you want a page to be allowed access anonymously,
						// set the flag "allowAnon" to true in the route defintion
						//authService.fetchCurrentUser({
						//	success: fetchCurrentUser
						//});
						$location.path('/login');
						event.preventDefault();
					}
				});

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
