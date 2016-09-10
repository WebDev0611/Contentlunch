'use strict';

(function($) {

    console.log('teste');

    var TwitterUser = Backbone.Model.extend({});

    var TwitterUserCollection = Backbone.Collection.extend({
        model: TwitterUser
    });

    var TwitterUserView = Backbone.View.extend({
        template: _.template($('#twitterUserTemplate').html()),
        tagName: 'li',
        render: function() {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        }
    });

    var listOfResults = new TwitterUserCollection();

    $('#twitterSearchButton').click(getTwitterFollowers);

    function getTwitterFollowers() {
        $.ajax({
            method: 'get',
            url: 'http://contentlaunch-2016.app/twitter/followers',
            data: $.param({ query: getSearchValue() })
        })
        .then(populateList);
    }

    function populateList(response) {

        console.log('Populating list');

        for (var i = 0; i < response.length; i++) {
            var resource = response[i];
            var user = new TwitterUser({
                name: resource.name,
                screen_name: resource.screen_name,
                description: resource.description,
                profile_image_url: resource.profile_image_url.replace('_normal', '_400x400')
            });

            var element = new TwitterUserView({ model: user });
            element.render();
            $('#twitterUserList').append(element.el);
        }

        // listOfResults.remove(listOfResults.models);
        // listOfResults.add(response.map(twitterUserMap));

        // listOfResults.on('add', function(m){
        //     var result = new TwitterUserView({ model: m });
        //     result.render();
        //     $('#twitterUserList').append(result.el);
        //     result.$el.fadeIn(250);
        // });

    }

    function twitterUserMap(user) {
        return new TwitterUser({
            name: user.name,
            screen_name: user.screen_name,
            description: user.description
        });
    }

    function getSearchValue() {
        return $('#twitterSearchField').val();
    }

})(jQuery);