launch.module.controller('AgencyController', [
    "$scope", "AuthService",
    function($scope, authService) {
        var self = this;

        self.init = function() {
            var u = authService.userInfo();
            var a = authService.accountInfo();
            console.log(a);

        }

        self.init();
    }
]);