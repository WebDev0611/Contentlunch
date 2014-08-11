﻿launch.module.factory('AccountService', function($resource, $upload, ModelMapperService, SessionService) {
	var accounts = $resource('/api/account/:id', { id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.account.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.account.parseResponse },
		update: { method: 'PUT', transformRequest: ModelMapperService.account.formatRequest, transformResponse: ModelMapperService.account.parseResponse },
		insert: { method: 'POST', transformRequest: ModelMapperService.account.formatRequest, transformResponse: ModelMapperService.account.parseResponse },
		delete: { method: 'DELETE' },
		getSubscription: { method: 'GET' }
	});

	var accountSubscriptions = $resource('/api/account/:id/subscription', { id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.subscription.parseResponse },
		save: { method: 'POST', transformRequest: ModelMapperService.subscription.formatRequest }
	});

	var subscriptions = $resource('/api/subscription/:id', { id: '@id' }, {
		get: { method: 'GET', transformResponse: ModelMapperService.subscription.parseResponse },
		query: { method: 'GET', isArray: true, transformResponse: ModelMapperService.subscription.parseResponse },
		save: { method: 'PUT', transformRequest: ModelMapperService.subscription.formatRequest }
	});

    var brainstorms = $resource('/api/account/:account_id/:content_type/:content_id/:campaign_id/brainstorm/:id',
        { account_id : '@account_id', content_type : '@content_type', content_id : '@content_id', campaign_id: '@campaign_id', id : '@id'},
        {
            get: { method: 'GET', transformRequest: ModelMapperService.brainstorm.formatRequest, transformResponse: ModelMapperService.brainstorm.parseResponse, isArray: true },
            insert: { method: 'POST', transformRequest: ModelMapperService.brainstorm.formatRequest, transformResponse: ModelMapperService.brainstorm.parseResponse },
            delete: { method: 'DELETE' }
        }
    );

	return {
		query: function(callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return accounts.query(null, success, error);
		},
		get: function(id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			var account = accounts.get({ id: id }, success, error);

			account.$promise.then(function(acct) {
				var loggedInUser = ModelMapperService.auth.fromCache(SessionService.get(SessionService.USER_KEY));

				if (!acct.subscription) {
					acct.subscription = accountSubscriptions.get({ id: acct.id }, function(subscription) {
						if (!loggedInUser.isGlobalAdmin) {
							SessionService.set(SessionService.ACCOUNT_KEY, acct);
						}
					}, error);
				} else {
					if (!loggedInUser.isGlobalAdmin) {
						SessionService.set(SessionService.ACCOUNT_KEY, acct);
					}
				}
			});

			return account;
		},
		update: function(account, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return accounts.update({ id: account.id }, account, success, error);
		},
		add: function(account, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return accounts.insert(null, account, success, error);
		},
		delete: function(account, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return accounts.delete({ id: account.id }, account, success, error);
		},
		addUser: function(accountId, userId, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;
			var resource = $resource('/api/account/:id/add_user', { id: '@id' }, {
				insert: {
					method: 'POST',
					transformRequest: function(uid) {
						return JSON.stringify({ user_id: uid });
					}
				}
			});

			return resource.insert({ id: accountId }, userId, success, error);
		},
		updateAccountSubscription: function(accountId, subscription, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			accountSubscriptions.save({ id: accountId }, subscription, success, error);
		},
		getSubscriptions: function(callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return subscriptions.query(null, success, error);
		},
		getSubscription: function(id, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return subscriptions.get({ id: id }, success, error);
		},
		saveSubscription: function(subscription, callback) {
			var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
			var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

			return subscriptions.save(null, subscription, success, error);
		},
		getNewAccount: function() {
			var account = new launch.Account();

			account.creditCard = new launch.CreditCard();
			account.bankAccount = new launch.BankAccount();
			account.subscription = null;

			return account;
		},
        getNewBrainstorm : function(user_id, account_id, content_type, concept_id) {
            var brainstorm = new launch.Brainstorm();
            brainstorm.user_id = user_id;
            brainstorm.account_id = account_id;
            brainstorm.content_type = content_type;
            brainstorm.content_id = concept_id;
            var now = new Date();
            now.setSeconds(0);
            now.setMilliseconds(0);
            now.setMinutes(0);
            brainstorm.datetime = now;
            brainstorm.date = moment(now).format('YYYY-MM-DD');
            brainstorm.time = now.getTime();
            return brainstorm;
        },
        addBrainstorm : function(brainstorm, callback) {
            var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
            var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

            return brainstorms.insert({
                account_id : brainstorm.account_id,
                content_type : brainstorm.content_type,
                content_id : brainstorm.content_id,
                campaign_id : brainstorm.campaign_id
            }, brainstorm, success, error);
        },
        removeBrainstorm : function(brainstorm, callback) {
            var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
            var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;

            return brainstorms.delete({
                account_id : brainstorm.account_id,
                content_type : brainstorm.content_type,
                content_id : brainstorm.content_id,
                campaign_id : brainstorm.campaign_id,
                id : brainstorm.id
            }, brainstorm, success, error);
        },
        getBrainstorms: function(accountId, contentType, contentId, callback) {
            var success = (!!callback && $.isFunction(callback.success)) ? callback.success : null;
            var error = (!!callback && $.isFunction(callback.error)) ? callback.error : null;
            if(contentType == 'campaign') {
                return brainstorms.get({
                    account_id : accountId,
                    content_type : contentType,
                    campaign_id : contentId
                }, success, error);
            }
            else {
                return brainstorms.get({
                    account_id : accountId,
                    content_type : contentType,
                    content_id : contentId
                }, success, error);
            }
        },
		resendCreationEmail: $resource('/api/account/:id/resend_creation_email', { id: '@id' }),
		requestUpdate: $resource('/api/account/request_update'),
		addFile: function (accountId, file, description, callback) {
			var data = { description: launch.utils.isBlank(description) ? null : description };

			$upload.upload({
				url: '/api/account/' + accountId + '/uploads',
				method: 'POST',
				data: data,
				file: file
			}).progress(function (e) {
				if (!!callback && $.isFunction(callback.progress)) {
					callback.progress(e);
				}
			}).success(function (data, status, headers, config) {
				if ((!!callback && $.isFunction(callback.success))) {
					callback.success(ModelMapperService.uploadFile.fromDto(data));
				}
			}).error(function (data, status, headers, config) {
				if (!!callback && $.isFunction(callback.error)) {
					callback.error({ data: data, status: status, headers: headers, config: config });
				}
			});
		},
		updateFile: function (accountId, id, file, description, callback) {
			var data = { description: launch.utils.isBlank(description) ? null : description };

			$upload.upload({
				url: '/api/account/' + accountId + '/uploads/' + id,
				method: 'PUT',
				data: data,
				file: file
			}).progress(function (e) {
				if (!!callback && $.isFunction(callback.progress)) {
					callback.progress(e);
				}
			}).success(function (data, status, headers, config) {
				if ((!!callback && $.isFunction(callback.success))) {
					callback.success(ModelMapperService.uploadFile.fromDto(data));
				}
			}).error(function (data, status, headers, config) {
				if (!!callback && $.isFunction(callback.error)) {
					callback.error({ data: data, status: status, headers: headers, config: config });
				}
			});
		},
		deleteFile: function (accountId, id, callback) {
			$upload.upload({
				url: '/api/account/' + accountId + '/uploads/' + id,
				method: 'DELETE',
				data: null
			}).progress(function (e) {
				if (!!callback && $.isFunction(callback.progress)) {
					callback.progress(e);
				}
			}).success(function (data, status, headers, config) {
				if ((!!callback && $.isFunction(callback.success))) {
					callback.success(ModelMapperService.uploadFile.fromDto(data));
				}
			}).error(function (data, status, headers, config) {
				if (!!callback && $.isFunction(callback.error)) {
					callback.error({ data: data, status: status, headers: headers, config: config });
				}
			});
		}
	};
});
