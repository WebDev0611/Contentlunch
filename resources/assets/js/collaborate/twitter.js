'use strict';

(function($) {

    var currentPage = 1;
    var PER_PAGE = 20;

    /**
     * Backbone Models
     */
    var TwitterUser = Backbone.Model.extend({});


    /**
     * Backbone Collections
     */
    var TwitterUserCollection = Backbone.Collection.extend({
        model: TwitterUser
    });

    var listOfResults = new TwitterUserCollection();

    listOfResults.on('add', function(m) {
        var result = new TwitterUserView({ model: m });
        result.render();

        $('#twitterUserList').append(result.el);
        // result.$el.fadeIn(250);
    });


    /**
     * Backbone views
     */
    var TwitterUserView = Backbone.View.extend({
        template: _.template($('#twitterUserTemplate').html()),
        tagName: 'li',
        render: function() {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        }
    });

    var TwitterInviteMessageView = Backbone.View.extend({
        initialize: function() {
            this.listenTo(this.collection,"change",this.render);
            this.listenTo(this.collection,"update",this.render);
        },

        render: function() {
            if (this.collection.length > 0) {
                this.$el.html(this.collection.length + " users found. Select the users you want to invite to work on the project");
            } else {
                this.$el.html('');
            }
        }
    });

    new TwitterInviteMessageView({ el: '#twitter-alert', collection: listOfResults });


    /**
     * jQuery bindings
     */
    $('#twitterSearchButton').click(function() { getTwitterFollowers(); });
    $('.btn-showmore').click(showMore);

    $('.results').hide();
    $('.btn-showmore').hide();


    /**
     * Users Search & other functions
     */
    function getTwitterFollowers(page) {
        page = page || 1;

        if (page === 1) {
            $('#twitterUserList').html('');
        }

        $.ajax({
            method: 'get',
            url: 'http://contentlaunch-2016.app/twitter/followers',
            data: $.param({
                query: getSearchValue(),
                page: page,
                count: PER_PAGE
            })
        })
        .then(function(response) {
            updateResultCount(response);
            toggleShowMoreButton(response);
            populateList(response);
        });
    }

    function populateList(response) {
        listOfResults.remove(listOfResults.models);
        listOfResults.add(response.map(twitterUserMap));
    }

    function showMore() {
        currentPage++;
        getTwitterFollowers(currentPage);
    }

    function updateResultCount(response) {
        var count = response.length;

        $('.results .result-count').html(count);
        $('.results').fadeIn('fast');
    }

    function toggleShowMoreButton(response) {
        var count = response.length;

        if (count >= PER_PAGE) {
            $('btn-showmore').fadeIn('fast');
        }
        else {
            $('btn-showmore').fadeOut('fast');
        }
    }

    function getSearchValue() {
        return $('#twitterSearchField').val();
    }

    function twitterUserMap(resource) {
        return new TwitterUser({
            name: resource.name,
            screen_name: resource.screen_name,
            description: resource.description,
            profile_image_url: resource.profile_image_url.replace('_normal', '_400x400'),
            followers_count: resource.followers_count,
            friends_count: resource.friends_count
        });
    }

})(jQuery);