/* ideas views */

var IdeaTemplate = `
    <div class="plan-ideas-cell">
        <% var avatar = user.profile_image || '/images/avatar.jpg'; %>
        <img src="<%= avatar %>" alt="#" class="plan-ideas-img">
    </div>
    <div class="plan-ideas-cell">
        <h5 class="plan-ideas-title"><%= name %></h5>
        <span class="plan-ideas-text"><%= text %></span>
    </div>

    <div class="plan-ideas-cell">
        <span class="plan-ideas-text"><%= created_diff.toUpperCase() %></span>
    </div>
    <div class="plan-ideas-cell">
        <span class="plan-ideas-text">UPDATED: <%= updated_diff.toUpperCase() %></span>
    </div>

    <div class="plan-ideas-cell">
        <div class="plan-ideas-dropdown">
            <button type="button" class="button button-action" data-toggle="dropdown">
                <i class="icon-add-circle"></i>
            </button>

            <ul class="dropdown-menu dropdown-menu-right">
                <!--<li id="write-it-btn">
                    <a href="javascript:;">Write It</a>
                </li> -->
                <li id="park-it-btn">
                    <a href="javascript:;">Park It</a>
                </li>
                <li id="edit-it-btn">
                    <a href="javascript:;">Edit It</a>
                </li>
               <li id="unpark-it-btn">
                    <a href="javascript:;">Unpark It</a>
                </li>
                <!--
                //should pre populate a create content form with the relevant data
                <li id="socialize-it-btn">
                    <a href="javascript:;">Socialize It</a>
                </li> -->
            </ul>
        </div>
    </div>
`;

var idea_view = Backbone.View.extend({
    className: "plan-ideas-container",

    events:{
        "click li#write-it-btn": "write",
        "click li#edit-it-btn": "edit",
        "click li#park-it-btn": "park",
        "click li#unpark-it-btn": "activate",
    },

    template: _.template(IdeaTemplate),

    initialize: function() {
        this.render();
    },

    render: function() {
        this.$el.html(this.template(this.model.attributes));

        if (this.model.get('status') == 'parked') {
            this.$el.find('#park-it-btn').hide();
        } else {
            this.$el.find('#unpark-it-btn').hide();
            this.$el.find('#park-it-btn').show();
        }

        return this;
    },

    edit: function() {
        window.location.href = '/idea/' + this.model.get('id');
    },

    write: function() {
        window.location.href = '/idea/write/' + this.model.get('id');
    },

    park: function() {
        return $.ajax({
            url: '/idea/park',
            data: {idea_id:this.model.get('id')},
            type:'post',
            headers: {
                'X-CSRF-TOKEN': $('input[name=_token]').val()
            }
        })
        .then(function(res) {
            console.log('parked' + this.model.attributes.id);
            //show msg
        });
    },

    activate: function() {
        return $.ajax({
            url: '/idea/activate',
            data: {idea_id:this.model.get('id')},
            type:'post',
            headers: {
                'X-CSRF-TOKEN': $('input[name=_token]').val()
            }
        })
        .then(function(res) {
            console.log('parked' + this.model.attributes.id);
        });
    }

});

var idea_container_view = Backbone.View.extend({
    status: 'active',
    events: {},

    initialize: function() {
        this.listenTo(this.collection,'update',this.updated);
    },

    updated: function() {
        this.render(this.status);
    },

    render: function(status) {
        this.status = status || 'active';
        var view = this;

        this.$el.html('');
        var active = this.collection.where({status:this.status});

        _.each(active, function(m) {
            view.$el.append(new idea_view({model: m}).$el);
        });
        return this;
    }
});

var recent_ideas_view = Backbone.View.extend({
    idea_views: [],

    initialize: function() {
        var that = this;
        this.listenTo( this.collection,'update', this.render );
        this.render();
    },
    render: function() {
        var that = this;

        if (that.collection.length > 0) {
            that.$el.find('.idea-empty').remove();
            that.collection.each(function(m) {
                that.idea_views.push(new recent_view({ model: m }));
            });
        } else {
            that.$el.append($('<div class="dashboard-ideas-container idea-empty"><div class="dashboard-ideas-cell">0 Ideas: <a href="/plan">Create One</a></div></div>'));
        }

        this.idea_views.forEach(function(v) {
            v.$el.hide();
            v.$el.fadeIn();
            that.$el.append( v.el );
        });

        return this;
    }
});

var recent_view = Backbone.View.extend({
    tagName: "div",
    className: "dashboard-ideas-container",
    events:{
        "mouseenter": "show_hover",
        "mouseleave": "hide_hover",
    },
    template: _.template(IdeaTemplate),

    initialize: function() {
        this.$el.append(this.template(this.model.attributes));
    },
    render: function() {
        return this;
    },
    show_hover: function() {
        this.$el.find('.idea-hover').toggleClass('hidden');
    },
    hide_hover: function() {
        this.$el.find('.idea-hover').toggleClass('hidden');
    },
});