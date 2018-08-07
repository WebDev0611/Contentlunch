'use strict';

var collaborators_collection = Backbone.Collection.extend({
    url: '/api/account/members',
    model: CollaboratorModel,

    fetch(options = {}) {
        options['X-CSRF-TOKEN'] = getToken();

        return Backbone.Collection.prototype.fetch.call(this, options);
    },
});

