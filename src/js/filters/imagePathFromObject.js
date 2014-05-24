angular.module('launch')

// input:   imageObj | imagePathFromObject:'user'
// returns: path to file on server
.filter('imagePathFromObject', [function () {
    return function (imageObj, type) {
        // generic fallback image
        var path = '/assets/images/' + (type || 'user') + '.svg';
        if (imageObj && imageObj.path && imageObj.filename)
            path = imageObj.path + imageObj.filename;
        return path.replace(/^\/public/, '');
    }; 
}]);