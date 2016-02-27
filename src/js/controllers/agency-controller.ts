///<reference path="../launch.ts"/>

module launchts {
    class AgencyController {
        public static $inject = ["$scope", "AuthService", "AgencyService", "userInfo"];

        public addingClient:boolean = false;
        public clients:Array<any> = [];
        public newClient:{name:string};

        constructor (private $scope, private authService, private agencyService, private userInfo) {
            this.init();
        }

        protected init() {
            var a = this.authService.accountInfo();
            this.agencyService.loadClients(this.userInfo.account.id).$promise.then( (clients) => {
                this.clients = clients;
                if (clients.length == 0) {
                    this.addClient();
                }
            });
            this.createStub();
            console.log(a);
        }

        protected createStub() {
            this.newClient = {
                name: "",
            };
        }

        public submitNewClient() {
            this.agencyService.addClient(this.userInfo.account.id, this.newClient).$promise.then( (newClient) => {
                this.clients.push(newClient);
                this.cancelAddClient();
            });
        }

        public addClient() {
            this.addingClient = true;
        }

        public cancelAddClient() {
            this.addingClient = false;
            this.createStub();
        }

    }


    launch.module.controller('AgencyController', AgencyController);
}