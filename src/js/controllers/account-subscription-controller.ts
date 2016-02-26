// Controller for the subscription form used in the account page.

launch.module.controller('AccountSubscriptionController', [
    '$scope', "AuthService", "AccountService", "$modal", "AnalyticsService",
    function ($scope,
              AuthService,
              AccountService,
              $modal,
              AnalyticsService) {
        var self = this;

        var pendingAction;

        debugger

        // We will always have a cached version by this time.
        var userInfo;
        AuthService.validateCurrentUser().then((user)=>{
            userInfo = user
        });

        self.account = userInfo.account;

        self.init = function () {
            $scope.refreshMethod();

            self.period = "annual";


            self.stripeHandler = StripeCheckout.configure({
                // TODO: Marc Get the right stripe key in here
                key: 'pk_test_9WtB8kfnBxpSgEX7MMwOkA82',
                image: '/assets/images/cl-wave.png',
                locale: 'auto',
                token: function(token) {
                    console.log("Made token!" + token);
                    // Use the token to create the charge with a server-side script.
                    // You can access the token ID with `token.id`
                    pendingAction(token);
                }
            });
        };

        self.planPeriod = function(type) {
            self.period = type;
        }

        self.setupSubscription = function(stripeToken, plan){
            console.log("Subscribe " + stripeToken + " to " + plan.name);
            AnalyticsService.trackEvent('subscribe', {'plan':plan.name});
            AccountService.updateAccountSubscription(tempAccount.id, {plan_id:plan.id, token:stripeToken['id']}).$promise.then(function(result){
                console.log(result);
            });
        };

        self.setupPaymentDetails = function(stripeToken) {
            AnalyticsService.trackEvent('changePaymentDetails', {});
            AccountService.updatePayment(tempAccount.id, {token:stripeToken['id']}).$promise.then(function(result){
                console.log(result);
            });
        }

        self.cancelSubscription = function($event) {
            $event.preventDefault();

            var m = $modal.open({
                templateUrl: 'cancel-confirm-window.html',
                controller: "CancelConfirmController",
                controllerAs: "ctrl"
            });

            m.result.then(function(reason){
               AnalyticsService.trackEvent('cancelSubscription', {reason: reason});
               AccountService.cancelSubscription(tempAccount.id);

            });

            //AccountService.cancelSubscription(tempAccount.id);
        }

        self.changePaymentDetails = function($event) {
            $event.preventDefault();
            pendingAction = self.setupPaymentDetails;
            self.stripeHandler.open({
                name: 'Content Launch',
                panelLabel: 'Update',
                email: user.email
            });
        }


        self.collectPaymentDetails = function($event, subscription_plan){
            $event.preventDefault();
            AnalyticsService.trackEvent('collectPayment', {'plan':subscription_plan.name});
            pendingAction = function(token) {
                self.setupSubscription(token, subscription_plan)
            }
            self.stripeHandler.open({
                name: 'Content Launch',
                description: subscription_plan.name,
                panelLabel: 'Subscribe',
                email: user.email
            });
        }

        self.init();
    }
]);