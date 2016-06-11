/// <reference path='../launch.ts' />
var launchts;
(function (launchts) {
    var AuthService = (function () {
        function AuthService($window, $location, $resource, $sanitize, accountId) {
            var _this = this;
            this.$window = $window;
            this.$location = $location;
            this.$resource = $resource;
            this.$sanitize = $sanitize;
            this.accountId = accountId;
            this.cachedValidation = null;
            this.fetchCurrentUser = function (callback) {
                var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
                var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;
                return _this.authenticate.fetchCurrentUser({ accountId: _this.accountId }, function (r) {
                    if (r.id) {
                        _this.cacheSession(r);
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
            this.validateCurrentUser = function () {
                console.log("Validating user");
                if (!_this.cachedValidation) {
                    console.log("NOT using cached validation");
                    _this.cachedValidation = _this.authenticate.fetchCurrentUser({ accountId: _this.accountId }).$promise;
                    _this.cachedValidation.then(function (r) {
                        if (r.id) {
                            console.log("Retrieved auth info");
                            _this.cacheSession(r);
                        }
                    });
                    _this.cachedValidation.catch(function () {
                        _this.cachedValidation = null; // do not cache negative results
                    });
                }
                return _this.cachedValidation;
            };
            this.modelMapper = new launch.ModelMapper($location, this);
            this.authenticate = this.$resource('/api/auth/:accountId', null, {
                login: { method: 'POST', transformResponse: this.modelMapper.auth.parseResponse },
                fetchCurrentUser: { method: 'GET', transformResponse: this.modelMapper.auth.parseResponse }
            });
            this.guestCollaborator = this.$resource('/api/guest-collaborators/me', null, {
                get: { method: 'GET', transformResponse: this.modelMapper.guestCollaborator.parseResponse }
            });
            this.confirmres = this.$resource('/api/auth/confirm', null, {
                confirm: { method: 'POST', transformResponse: this.modelMapper.auth.parseResponse }
            });
            this.impersonateres = this.$resource('/api/auth/impersonate', null, {
                save: { method: 'POST', transformResponse: this.modelMapper.user.parseResponse }
            });
        }
        AuthService.prototype.cacheSession = function (user) {
            this.authenticated_key = true;
            this.user_key = user;
            this.account_key = user.account;
        };
        ;
        AuthService.prototype.uncacheSession = function () {
            this.authenticated_key = null;
            this.user_key = null;
            this.account_key = null;
        };
        ;
        AuthService.prototype.login = function (username, password, remember, callback) {
            var _this = this;
            var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
            var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;
            return this.authenticate.login({
                email: this.$sanitize(username),
                password: this.$sanitize(password),
                remember: remember
            }, function (r) {
                var user = r;
                _this.cacheSession(user);
                if ($.isFunction(success)) {
                    success(user);
                }
            }, error);
        };
        AuthService.prototype.logout = function () {
            var _this = this;
            this.uncacheSession();
            return this.$resource('/api/auth/logout').get(function (r) {
                _this.uncacheSession();
            });
        };
        AuthService.prototype.isLoggedIn = function () {
            return this.authenticated_key; // Boolean(this.SessionService.get(this.SessionService.AUTHENTICATED_KEY));
        };
        AuthService.prototype.userInfo = function () {
            if (!this.isLoggedIn()) {
                return null;
            }
            return this.modelMapper.auth.fromCache(this.user_key);
        };
        AuthService.prototype.accountInfo = function () {
            if (!this.isLoggedIn()) {
                return null;
            }
            return this.modelMapper.account.fromCache(this.account_key);
        };
        AuthService.prototype.fetchGuestCollaborator = function (callback) {
            var _this = this;
            var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
            var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;
            return this.guestCollaborator.get(null, function (r) {
                if (r.id) {
                    _this.cacheSession(r);
                }
                if ($.isFunction(success)) {
                    success(r);
                }
                if (!r.id) {
                    return;
                }
            }, error);
        };
        AuthService.prototype.forgotPassword = function (email, callback) {
            var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
            var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;
            var method = this.$resource('/api/auth/forgot_password', null, {
                reset: { method: 'POST' }
            });
            return method.reset({ email: email }, success, error);
        };
        AuthService.prototype.confirm = function (code, callback) {
            var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
            var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;
            return this.confirmres.confirm(null, { code: code }, success, error);
        };
        AuthService.prototype.impersonate = function (accountId) {
            var _this = this;
            this.impersonateres.save({ account_id: accountId }, function (r) {
                _this.uncacheSession();
                _this.fetchCurrentUser({
                    success: function (user) {
                        _this.$window.location.href = '/';
                    }
                });
            });
        };
        AuthService.prototype.impersonateReset = function () {
            var _this = this;
            this.impersonateres.save({ reset: 'true' }, function (r) {
                _this.uncacheSession();
                _this.fetchCurrentUser({
                    success: function (user) {
                        this.$window.location.href = '/accounts';
                    }
                });
            });
        };
        AuthService.$inject = ["$window", "$location", "$resource", "$sanitize", "accountId"];
        return AuthService;
    })();
    launchts.AuthService = AuthService;
    launch.module.service('AuthService', AuthService);
})(launchts || (launchts = {}));
//# sourceMappingURL=auth-service.js.map