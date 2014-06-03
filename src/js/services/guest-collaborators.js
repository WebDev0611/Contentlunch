angular.module('launch')

.factory('guestCollaborators', [function () {
    return {
        parseFriends: function (connection, friends) {
            if (connection.connection_provider == 'linkedin') {
                connection.friends = _(friends.plain()).map(function (friend) {
                    connection.friendsHeaders = ['Name', 'Position', 'Industry'];
                    
                    var arr = [
                        '<a href="' + friend.publicProfileUrl + '" target="_blank">' + 
                            friend.firstName + ' ' + friend.lastName + '</a>',
                        friend.headline,
                        friend.industry
                    ];

                    arr.id = friend.id;

                    return arr;
                }).reject(function (friend) {
                    // people can choose not to share their profile and they
                    // will have an ID of "private" which not only breaks
                    // our ng-repeat, but we can't send them a DM anyway
                    return friend.id == 'private'; 
                }).value();
            } else { // it's twitter
                connection.friends = _.map(friends.plain(), function (friend) {
                    connection.friendsHeaders = ['Name', 'Geography', 'Username'];
                    
                    var arr = [
                        friend.name,
                        friend.location,
                        '<a href="https://twitter.com/' + friend.screen_name + 
                            '" target="_blank">@' + friend.screen_name + '</a>'
                    ];

                    arr.id = friend.id;

                    return arr;
                });
            }
        },
        parseGroups: function (connection, groups) {
            if (!groups) return;
            connection.groups = _.map(groups, function (group) {
                return {
                    id: group.group.id,
                    name: group.group.name
                }
            });
        }
    };
}]);