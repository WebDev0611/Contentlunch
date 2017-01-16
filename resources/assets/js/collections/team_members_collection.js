'use strict';

var team_members_collection = Backbone.Collection.extend({
    model: team_member_model,
    url: '/api/account/members',

    fetch(options = {}) {
        options['X-CSRF-TOKEN'] = getToken();

        return Backbone.Collection.prototype.fetch.call(this, options);
    },
});