'use strict';

var team_member_model = Backbone.Model.extend({
    defaults:{
        "name": "Jason Simmons",
        "email": "jasonsimm@google.com",
        "image": "/images/avatar.jpg",
        "tasks": "35"
    }
});