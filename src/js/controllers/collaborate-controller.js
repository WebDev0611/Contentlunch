launch.module.controller('CollaborateController', 
        ['$scope', '$location', 
function ($scope,   $location) {
	$scope.pagination = {
        pageSize: 5,
        currentPage: 1,
    };

    var fakeData = {
        icon: 'head',
        type: 'Content',
        title: "I'm the title!",
        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Tuo vero id quidem, inquam, arbitratu. Rationis enim perfectio est virtus.',
        internalCollaborators: {
            firstName: 'Bob',
            lastName: 'Kurtz'
        },
        guestCollaborators: {
            firstName: 'George',
            lastName: 'Mauer'
        }
    };

    $scope.content = _.map(_.range(1, 30), function (i) {
        var clone = _.clone(fakeData);
        clone.id = i;
        clone.title += ' ' + i;
        clone.internalCollaborators = _.map(_.range(1, _.random(2, 7)), function (i) { 
            var clone = _.clone(fakeData.internalCollaborators);
            clone.id = i;
            return clone;
        });
        clone.guestCollaborators    = _.map(_.range(1, _.random(2, 7)), function (i) { 
            var clone = _.clone(fakeData.guestCollaborators);
            clone.id = i;
            return clone;
        });
        return clone;
    });

    $scope.selected = $scope.content[0];
    console.log($scope.content);
}]);