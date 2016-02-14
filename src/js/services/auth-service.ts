/// <reference path='../launch.ts' />

module launchts {

	class AuthService {
		static $inject = ["$window", "$location", "$resource", "$sanitize", "SessionService"];

		// WE CANNOT PASS IN A ModelMapperService BECAUSE IT WOULD CAUSE A CIRCULAR DEPENDENCY.
		// INSTEAD, CREATE OUR OWN INSTANCE OF THE ModelMapper CLASS.
		protected modelMapper;
		protected authenticate;
		protected guestCollaborator;
		protected confirmres;
		protected impersonateres;
		protected cachedValidation:ng.IPromise<any> = null;

		constructor(protected $window,
					protected $location,
					protected $resource,
					protected $sanitize,
					protected SessionService) {

			this.modelMapper = new launch.ModelMapper($location, this);

			this.authenticate = this.$resource('/api/auth', null, {
				login: {method: 'POST', transformResponse: this.modelMapper.auth.parseResponse},
				fetchCurrentUser: {method: 'GET', transformResponse: this.modelMapper.auth.parseResponse}
			});

			this.guestCollaborator = this.$resource('/api/guest-collaborators/me', null, {
				get: {method: 'GET', transformResponse: this.modelMapper.guestCollaborator.parseResponse}
			});

			this.confirmres = this.$resource('/api/auth/confirm', null, {
				confirm: {method: 'POST', transformResponse: this.modelMapper.auth.parseResponse}
			});

			this.impersonateres = this.$resource('/api/auth/impersonate', null, {
				save: {method: 'POST', transformResponse: this.modelMapper.user.parseResponse}
			});
		}


		protected cacheSession(user) {
			this.SessionService.set(this.SessionService.AUTHENTICATED_KEY, true);
			this.SessionService.set(this.SessionService.USER_KEY, user);
			this.SessionService.set(this.SessionService.ACCOUNT_KEY, user.account);
		};

		protected uncacheSession() {
			this.SessionService.unset(this.SessionService.AUTHENTICATED_KEY);
			this.SessionService.unset(this.SessionService.USER_KEY);
			this.SessionService.unset(this.SessionService.ACCOUNT_KEY);
		};

		public fetchCurrentUser = (callback) => {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return this.authenticate.fetchCurrentUser(null, (r) => {
				if (r.id) {
					this.cacheSession(r);
				}
				else {
					if ($.isFunction(error)) {
						error(r);
					}
					return;
				}

				if ($.isFunction(success)) {
					success(r);
				}

			}, error);
		};

		public validateCurrentUser = () => {
			console.log("Validating user");
			if (!this.cachedValidation) {
				console.log("NOT using cached validation");
				this.cachedValidation = this.authenticate.fetchCurrentUser().$promise;

				this.cachedValidation.then((r) => {
					if (r.id) {
						console.log("Retrieved auth info");
						this.cacheSession(r);
					}
				});

				this.cachedValidation.catch(() => {
					this.cachedValidation = null;  // do not cache negative results
				});
			}

			return this.cachedValidation;
		}


		public login(username, password, remember, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return this.authenticate.login({
					email: this.$sanitize(username),
					password: this.$sanitize(password),
					remember: remember
				},
				(r) => {
					// I think this has already been run through fromDto
					// var user = this.modelMapper.auth.fromDto(r);
					var user = r;

					this.cacheSession(user);

					if ($.isFunction(success)) {
						success(user);
					}
				},
				error);
		}


		public logout() {
			this.SessionService.clear();

			return this.$resource('/api/auth/logout').get((r) => {
				this.uncacheSession();
			});
		}


		public isLoggedIn() {
			return Boolean(this.SessionService.get(this.SessionService.AUTHENTICATED_KEY));
		}

		public userInfo() {
			if (!this.isLoggedIn()) {
				return null;
			}

			return this.modelMapper.auth.fromCache(JSON.parse(this.SessionService.get(this.SessionService.USER_KEY)));
		}

		public accountInfo() {
			if (!this.isLoggedIn()) {
				return null;
			}

			return this.modelMapper.account.fromCache(JSON.parse(this.SessionService.get(this.SessionService.ACCOUNT_KEY)));
		}


		public fetchGuestCollaborator(callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return this.guestCollaborator.get(null, (r) => {

				if (r.id) {
					this.cacheSession(r);
				}

				if ($.isFunction(success)) {
					success(r);
				}

				if (!r.id) {
					return;
				}
			}, error);
		}

		public forgotPassword(email:string, callback:{success:Function, error:Function}) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			var method = this.$resource('/api/auth/forgot_password', null, {
				reset: {method: 'POST'}
			});

			return method.reset({email: email}, success, error);
		}

		public confirm(code, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return this.confirmres.confirm(null, {code: code}, success, error);
		}

		public impersonate(accountId:number) {
			this.impersonateres.save({account_id: accountId},
				(r) => {
					this.uncacheSession();
					this.fetchCurrentUser({
						success: (user) => {
							this.$window.location.href = '/';
						}
					});
				});
		}

		public impersonateReset() {
			this.impersonateres.save({reset: 'true'}, (r) => {
				this.uncacheSession();
				this.fetchCurrentUser({
					success: function (user) {
						this.$window.location.href = '/accounts';
					}
				});
			});
		}
	}

	launch.module.service('AuthService', AuthService);
}