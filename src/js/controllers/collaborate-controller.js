launch.module.controller('CollaborateController', 
        ['$scope', '$rootScope', '$location', 'Restangular', '$q', 'AuthService', '$filter', '$routeParams', '$modal', 'guestCollaborators', 'NotificationService', 
function ($scope,   $rootScope,   $location,   Restangular,   $q,   AuthService,   $filter,   $routeParams,   $modal,   guestCollaborators,   notify) {
	$scope.pagination = {
        pageSize: 5,
        currentPage: 1,
    };

    // Get & Setup Our Data
    // -------------------------
    var user = AuthService.userInfo();

    var Account = Restangular.one('account', user.account.id), Collab;
    var requests = {
        users: Account.all('users').getList(),
    };

    $scope.invited = {};
    Restangular.extendModel('guest-collaborators', function (model) {
        $scope.invited[model.connection_user_id] = true;
        return model;
    });

    // sharing controllers since "list" is so simple
    if ($routeParams.id) {
        Collab = Account.one($routeParams.conceptType, $routeParams.id);
        requests.selected = Collab.get().then(function (selected) {
            // TODO: figure out how we're differentiating collaborators. but for now...
            selected.internalCollaborators = selected.collaborators;
            return selected;
        });
        requests.connections = Account.all('connections').getList({ 'provider[]': ['linkedin', 'twitter'] });
        requests.guestCollaborators = Collab.all('guest-collaborators').getList();
    } else {
        requests.list = $q.all({
            Content  : Account.all( 'content' ).getList({ status: 0 }),
            Campaign : Account.all('campaigns').getList({ status: 0 }),
        }).then(function (responses) {
            // merge lists and set the type & type_slug
            var response = _.reduce(responses, function (list, sublist, type) {
                _.each(sublist, function (item) {
                    item.type = type;
                    item.type_slug = type === 'Content' ? 'content' : 'campaigns';
                    // TODO: figure out how we're differentiating collaborators. but for now...
                    item.internalCollaborators = item.collaborators;
                    return list.push(item);
                });
                return list;
            }, []);

            return response; // _.sortBy(response, 'title');
        });
    }

    $q.all(requests).then(function (responses) {
        angular.extend($scope, responses);
        console.log(_.mapObject(responses, function (response, key) {
            return [key, response.plain ? response.plain() : response];
        }));
    });

    // Actions
    // -------------------------
    $scope.addInternalCollaborator = function (collaboratorToAdd) {
        $scope.showAddInternal = false;
        if (!_.isArray($scope.selected.internalCollaborators)) 
            $scope.selected.internalCollaborators = [];

        $scope.selected.all('collaborators').post({ 
            user_id: collaboratorToAdd.id 
        }).then(function () {
            $scope.selected.internalCollaborators.push(collaboratorToAdd);
        });
    };

    $scope.removeInternalCollaborator = function (collab) {
        $scope.selected.one('collaborators', collab.id).remove().then(function () {
            $rootScope.removeRow($scope.selected.internalCollaborators, collab.id);
        });
    };

    $scope.removeGuestCollaborator = function (collab) {
        collab.remove().then(function () {
            delete $scope.invited[collab.connection_user_id];
            $rootScope.removeRow($scope.guestCollaborators, collab.id);
        });
    };

    $scope.openInviteModal = function (connection) {
        console.log(connection.recipients);
        if (!connection.recipients || !connection.recipients.length) {
            notify.notify('Please choose at least one recepient.');
            return;
        }
        
        $modal.open({
            templateUrl: '/assets/views/collaborate/invite-modal.html',
            size: 'lg',
            controller: ['$scope', 'recipients', 'link', 'provider', function (_scope, recipients, link, provider) {
                console.log(recipients, link, provider);
                _scope.recipients = recipients;
                _scope.provider = provider;
                _scope.linkLength = (link || {}).len || 0;
                if (provider == 'twitter') _scope.linkLength += 2; // 2 newlines
            }],
            resolve: { 
                recipients: function () { return connection.recipients; },
                provider: function () { return connection.connection_provider; },
                link: connection.connection_provider == 'twitter' ?
                        connection.one('twitter-link-length').get().then(function (link) {
                            console.log(link);
                            return link;
                        }) :
                        function () { return { len: 0 }; }
            }
        }, angular.noop).result.then(function (message) {
            return connection.all('message').post({
                friends:   _.mapObject(connection.recipients, function (recip) {
                    return [recip.id, recip.name];
                }),
                message:   message,
                contentId: $routeParams.id
            });
        }, angular.noop).then(function (response) {
            connection.recipients = [];
            if (!angular.isDefined(response)) return;

            response = response.plain();
            var allGood = _.all(_.values(response));

            if (allGood) {
                notify.success('Messages sent!');
            } else {
                var failedNames = _(response).map(function (success, id) {
                    return success || _.findById(connection.recipients, id).name;
                }).reject(function (item) {
                    return item === true;
                }).value();

                notify.error('All messages succeeded except for: ' + failedNames.join(', '));
            }

            return Collab.all('guest-collaborators').getList();
        }).then(function (guests) {
            if (!angular.isDefined(guests)) return;

            $scope.guestCollaborators = guests;
        }).catch(function (err) {
            console.error(err);
        });
    };

    $scope.toggleAccordion = function (connection) {
        if (!connection.accordionOpen) return;

        connection.spinner = true;

        connection.getList('friends').then(function (friends) {
            // this attaches connection.friends and connections.friendsHeaders
            // and formats the data and fields as needed to work with the template
            guestCollaborators.parseFriends(connection, friends);
        }, function (err) {
            console.error(err);
            notify.error('There was an error getting your followers/connections.');
        }).then(function () {
            connection.spinner = false;
        });
    };


    // Helpers
    // -------------------------
    $scope.setViewable = function(friends, hideInvited) {
        if (!hideInvited) return friends;
        return _.reject(friends, function (friend) {
            return $scope.invited[friend.id];
        });
    };

    $scope.formatUserItem = function (item, element, context) {
        if (!item.text) return element.attr('placeholder');
        var user = _.findById($scope.users, item.id)[0] || {};
        var style = ' style="background-image: url(\'' + $filter('imagePathFromObject')(user.image) + '\')"';

        return '<span class="user-image user-image-small"' + style + '></span> <span>' + item.text + '</span>';
    };
}]);