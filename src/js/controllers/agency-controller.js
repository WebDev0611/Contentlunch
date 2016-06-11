///<reference path="../launch.ts"/>
var launchts;
(function (launchts) {
    var AgencyController = (function () {
        function AgencyController($scope, authService, agencyService, userInfo) {
            this.$scope = $scope;
            this.authService = authService;
            this.agencyService = agencyService;
            this.userInfo = userInfo;
            this.addingClient = false;
            this.clients = [];
            this.init();
        }
        AgencyController.prototype.init = function () {
            var _this = this;
            var a = this.authService.accountInfo();
            this.agencyService.loadClients(this.userInfo.account.id).$promise.then(function (clients) {
                _this.clients = clients;
                if (clients.length == 0) {
                    _this.addClient();
                }
            });
            this.createStub();
            console.log(a);
        };
        AgencyController.prototype.createStub = function () {
            this.newClient = {
                name: ""
            };
        };
        AgencyController.prototype.submitNewClient = function () {
            var _this = this;
            this.agencyService.addClient(this.userInfo.account.id, this.newClient).$promise.then(function (newClient) {
                _this.clients.push(newClient);
                _this.cancelAddClient();
            });
        };
        AgencyController.prototype.addClient = function () {
            this.addingClient = true;
        };
        AgencyController.prototype.cancelAddClient = function () {
            this.addingClient = false;
            this.createStub();
        };
        AgencyController.$inject = ["$scope", "AuthService", "AgencyService", "userInfo"];
        return AgencyController;
    })();
    launch.module.controller('AgencyController', AgencyController);
})(launchts || (launchts = {}));
//# sourceMappingURL=agency-controller.js.map