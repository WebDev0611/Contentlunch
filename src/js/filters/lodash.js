// filter wrapper for lodash
angular.module('launch')

// {{ tags | _:'flatten': 'tag' }} returns the result from _.flatten(tags, 'tag');
.filter('_', [function () {
    return function (input, fnName) {
        if (!_[fnName]) return input;
        var args = _.toArray(arguments).slice(2);
        args.unshift(input);
        return _[fnName].apply(_, args);
    };
}]);