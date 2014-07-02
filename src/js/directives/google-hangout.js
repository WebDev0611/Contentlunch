angular.module('launch')

.factory('plusone', [function () {
    var theCallback;
    var defaults = {
        apps: null,
        size: 72,
        type: 'normal',
        topic: null,
        invitees: null
    };

    // this is only included once
    $.getScript('https://apis.google.com/js/plusone.js', function () {
        if (theCallback) {
            theCallback();
            theCallback = undefined;
        }
    });

    return { 
        callback: function (cb) {
            if (!theCallback) {
                cb();
            } else {
                theCallback = cb;
            }
        },
        renderGoogleButton: function ($elem, opts) {
            opts = _.extend({}, defaults, opts);
            opts.apps = opts.apps && JSON.parse(opts.apps);
            opts.topic = opts.topic || $elem.val();

            gapi.hangout.render($elem[0], {
                render: 'createhangout',
                topic: opts.topic,
                initial_apps: opts.apps,
                hangout_type: opts.type,
                widget_size: parseInt(opts.size),
                invites: _.map(opts.invitees || [], function (email) {
                    return {
                        id: email,
                        invite_type: 'EMAIL'
                    };
                })
            });
        }
    };
}])

.directive('googleHangout', ['plusone', function (plusone) {
    return {
        restrict: 'AE',
        scope: {
            invitees: '=',
            topic:    '='
        },
        link: function (scope, elem, attrs) {
            plusone.callback(function () {
                plusone.renderGoogleButton(elem);

                scope.$watch(function () {
                    return {
                        invitees: scope.invitees,
                        topic: scope.topic,
                    };
                }, function (opts) {
                    plusone.renderGoogleButton(elem, opts);
                }, true);
            });
        }
    };
}]);