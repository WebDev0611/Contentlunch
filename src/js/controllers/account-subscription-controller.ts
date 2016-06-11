/// <reference path='../launch.ts' />

module launchts {

    // Controller for the subscription form used in the account page

    import IModalService = angular.ui.bootstrap.IModalService;
    export class AccountSubscriptionController {

        public static $inject = ['$scope', "AuthService", "AccountService", "$modal", "AnalyticsService"];

        protected userInfo;
        protected account;
        protected period:string;
        protected stripeHandler;
        protected pendingAction:Function;

        constructor(protected $scope, protected authService:AuthService,
                    protected accountService, protected $modal:IModalService,
                    protected analyticsService) {



            // We will always have a cached version by this time so this should be synchronous
            authService.validateCurrentUser().then((user)=> {
                this.userInfo = user;
                this.account = this.userInfo.account;
                console.log("1 " + this.userInfo);
            });


            this.period = "annual";

            this.stripeHandler = StripeCheckout.configure({
                // TODO: Marc Get the right stripe key in here
                key: 'pk_test_9WtB8kfnBxpSgEX7MMwOkA82',
                image: '/assets/images/cl-wave.png',
                locale: 'auto',
                token: (token) => {
                    console.log("Made token!" + token);
                    // Use the token to create the charge with a server-side script.
                    // You can access the token ID with `token.id`
                    this.pendingAction(token);
                }
            });
        };

        public planPeriod(type:string) {
            this.period = type;
        }

        public setupSubscription(stripeToken, plan) {
            console.log("Subscribe " + stripeToken + " to " + plan.name);
            this.analyticsService.trackEvent('subscribe', {'plan': plan.name});
            this.accountService.updateAccountSubscription(this.account.id, {
                plan_id: plan.id,
                token: stripeToken['id']
            }).$promise.then(function (result) {
                console.log(result);
            });
        };

        public setupPaymentDetails(stripeToken) {
            this.analyticsService.trackEvent('changePaymentDetails', {});
            this.accountService.updatePayment(this.account.id, {token: stripeToken['id']}).$promise.then(function (result) {
                console.log(result);
            });
        }

        public cancelSubscription($event) {
            $event.preventDefault();

            var m = this.$modal.open({
                templateUrl: 'cancel-confirm-window.html',
                controller: "CancelConfirmController",
                controllerAs: "ctrl"
            });

            m.result.then(function (reason) {
                this.analyticsService.trackEvent('cancelSubscription', {reason: reason});
                this.accountService.cancelSubscription(this.account.id);

            });
        }

        public changePaymentDetails = ($event) => {
            $event.preventDefault();
            this.pendingAction = this.setupPaymentDetails;
            this.stripeHandler.open({
                name: 'Content Launch',
                panelLabel: 'Update',
                email: this.userInfo.email
            });
        };


        public collectPaymentDetails = function ($event, subscription_plan) {
            $event.preventDefault();
            this.analyticsService.trackEvent('collectPayment', {'plan': subscription_plan.name});
            this.pendingAction = function (token) {
                this.setupSubscription(token, subscription_plan)
            };
            this.stripeHandler.open({
                name: 'Content Launch',
                description: subscription_plan.name,
                panelLabel: 'Subscribe',
                email: this.userInfo.email
            });
        }
    }


    launch.module.controller('AccountSubscriptionController', AccountSubscriptionController);
}