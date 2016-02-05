launch.module.controller('AgencyController', [
    "$scope", "AuthService", "AgencyService", "userInfo",
    function($scope, authService, agencyService, userInfo) {
        var self = this;
        self.addingClient = false;
        self.clients = [];

        self.newClient = {
            name: "",

        };


        self.init = function() {
            var u = authService.userInfo();
            var a = authService.accountInfo();
            agencyService.loadClients(userInfo.account.id).$promise.then(function(clients){
                self.clients = clients;
                if(clients.length == 0) {
                    self.addClient();
                }
            });
            console.log(a);
        }

        self.submitNewClient = function() {
            agencyService.addClient(userInfo.account.id, self.newClient).$promise.then(function(newClient){
               self.clients.push(newClient);
                self.cancelAddClient();
            });
        }

        self.addClient = function() {
            self.addingClient = true;
        }

        self.cancelAddClient = function() {
            self.addingClient = false;
        }

        self.init();
    }
]);