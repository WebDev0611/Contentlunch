'use strict';

var team_member_model = Backbone.Model.extend({
    defaults:{
        "name": "Unnamed user",
        "email": "No email added",
        "image": "/images/cl-avatar2.png",
        "tasks": "0"
    }
});