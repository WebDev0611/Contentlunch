/// <reference path='../launch.ts' />
var launchts;
(function (launchts) {
    var AccountSubscriptionController = (function () {
        function AccountSubscriptionController($scope, authService, accountService, $modal, analyticsService) {
            var _this = this;
            this.$scope = $scope;
            this.authService = authService;
            this.accountService = accountService;
            this.$modal = $modal;
            this.analyticsService = analyticsService;
            this.changePaymentDetails = function ($event) {
                $event.preventDefault();
                _this.pendingAction = _this.setupPaymentDetails;
                _this.stripeHandler.open({
                    name: 'Content Launch',
                    panelLabel: 'Update',
                    email: _this.userInfo.email
                });
            };
            this.collectPaymentDetails = function ($event, subscription_plan) {
                $event.preventDefault();
                this.analyticsService.trackEvent('collectPayment', { 'plan': subscription_plan.name });
                this.pendingAction = function (token) {
                    this.setupSubscription(token, subscription_plan);
                };
                this.stripeHandler.open({
                    name: 'Content Launch',
                    description: subscription_plan.name,
                    panelLabel: 'Subscribe',
                    email: this.userInfo.email
                });
            };
            // We will always have a cached version by this time so this should be synchronous
            authService.validateCurrentUser().then(function (user) {
                _this.userInfo = user;
                _this.account = _this.userInfo.account;
                console.log("1 " + _this.userInfo);
            });
            this.period = "annual";
            this.stripeHandler = StripeCheckout.configure({
                // TODO: Marc Get the right stripe key in here
                key: 'pk_test_9WtB8kfnBxpSgEX7MMwOkA82',
                image: '/assets/images/cl-wave.png',
                locale: 'auto',
                token: function (token) {
                    console.log("Made token!" + token);
                    // Use the token to create the charge with a server-side script.
                    // You can access the token ID with `token.id`
                    _this.pendingAction(token);
                }
            });
        }
        ;
        AccountSubscriptionController.prototype.planPeriod = function (type) {
            this.period = type;
        };
        AccountSubscriptionController.prototype.setupSubscription = function (stripeToken, plan) {
            console.log("Subscribe " + stripeToken + " to " + plan.name);
            this.analyticsService.trackEvent('subscribe', { 'plan': plan.name });
            this.accountService.updateAccountSubscription(this.account.id, {
                plan_id: plan.id,
                token: stripeToken['id']
            }).$promise.then(function (result) {
                console.log(result);
            });
        };
        ;
        AccountSubscriptionController.prototype.setupPaymentDetails = function (stripeToken) {
            this.analyticsService.trackEvent('changePaymentDetails', {});
            this.accountService.updatePayment(this.account.id, { token: stripeToken['id'] }).$promise.then(function (result) {
                console.log(result);
            });
        };
        AccountSubscriptionController.prototype.cancelSubscription = function ($event) {
            $event.preventDefault();
            var m = this.$modal.open({
                templateUrl: 'cancel-confirm-window.html',
                controller: "CancelConfirmController",
                controllerAs: "ctrl"
            });
            m.result.then(function (reason) {
                this.analyticsService.trackEvent('cancelSubscription', { reason: reason });
                this.accountService.cancelSubscription(this.account.id);
            });
        };
        AccountSubscriptionController.$inject = ['$scope', "AuthService", "AccountService", "$modal", "AnalyticsService"];
        return AccountSubscriptionController;
    })();
    launchts.AccountSubscriptionController = AccountSubscriptionController;
    launch.module.controller('AccountSubscriptionController', AccountSubscriptionController);
})(launchts || (launchts = {}));
//# sourceMappingURL=account-subscription-controller.js.map