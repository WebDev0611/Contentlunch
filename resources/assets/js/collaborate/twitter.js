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
        events: {
            'click .details': 'showDetails',
            'click .invite': 'invite'
        },

        template: _.template($('#twitterUserTemplate').html()),
        tagName: 'li',
        render: function() {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        },

        showDetails: function() {
            console.log('HEUEHUHEE');
            new TwitterDataModal({ el: "#modal-twitter-user-details", model: this.model });
        },

        invite: function() {
            // new TwitterInviteModal({ el: '#modal-invite-twitter-user', model: this.model });
        }
    });

    var TwitterDataModal = Backbone.View.extend({
        events: {
            "click .sidemodal-close": "dismiss",
            "click .invite-btn": "invite"
        },
        initialize: function(){
            console.log('new modal init!');
            this.render();
        },
        render: function() {
            var title = this.model.get('name') + '(@' + this.model.get('screen_name') + ')';
            var desc = this.model.get('description');
            var avatar = "<img src='" + this.model.get('profile_image_url') + "' alt='" + this.model.get('name') + "'>";

            this.$el.find('.title').text(title);
            this.$el.find('.desc').text(desc);
            this.$el.find('.user-avatar').html(avatar);
            this.$el.find('.friends-count').html(this.model.get('friends_count'));
            this.$el.find('.followers-count').html(this.model.get('followers_count'));

            $('#modal-twitter-user-details').modal('show');

            return this;
        },
        dismiss: function(){
            $('#modal-twitter-user-details').modal('hide');
        },
        invite: function(){
            // new influencer_invite_modal({el:"#modal-inviteinfluencer", model: this.model});
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