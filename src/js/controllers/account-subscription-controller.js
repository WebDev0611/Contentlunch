// Controller for the subscription form used in the account page.

launch.module.controller('AccountSubscriptionController', [
    '$scope', "AuthService", "AccountService",
    function ($scope, AuthService, AccountService) {
        var self = this;
        var user = AuthService.accountInfo();
        var pendingAction;

        var tempAccount = AuthService.accountInfo();


        self.init = function () {
            $scope.refreshMethod();

            self.period = "annual";
            self.account = AccountService.get(tempAccount.id);



            self.stripeHandler = StripeCheckout.configure({
                key: 'pk_test_9WtB8kfnBxpSgEX7MMwOkA82',
                image: '/img/documentation/checkout/marketplace.png',
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

        self.setupSubscription = function(stripeToken){

        };

        pendingAction = self.setupSubscription;

        self.collectPaymentDetails = function($event, annual){
            $event.preventDefault();
            self.stripeHandler.open({
                name: 'Content Launch',
                description: annual ? 'Professional (annual)' : 'Professional (monthly)',
                panelLabel: 'Subscribe',
                //amount: annual ? 8900*12 : 9900,
                email: user.email
            });
        }

        self.init();
    }
]);