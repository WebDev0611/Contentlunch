/* ideas views */

var idea_view = Backbone.View.extend({
    className: "plan-ideas-container",

    events:{
        "click li#write-it-btn": "write",
        "click li#edit-it-btn": "edit",
        "click li#park-it-btn": "park",
        "click li#unpark-it-btn": "activate",
    },

    template: _.template(`
        <div class="plan-ideas-cell cell-size-5">
            <% var avatar = user.profile_image || '/images/avatar.jpg'; %>
            <img src="<%= avatar %>" alt="#" class="plan-ideas-img">
        </div>
        <div class="plan-ideas-cell cell-size-25">
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
    `),

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

        active.forEach(function(model) {
            view.$el.append(new idea_view({ model: model }).$el);
        });

        return this;
    }
});

var recent_ideas_view = Backbone.View.extend({
    idea_views: [],

    initialize() {
        this.listenTo(this.collection, 'update', this.render);
        this.render();
    },

    render() {
        if (this.collection.length > 0) {
            this.appendToIdeaViews();
        } else {
            this.appendCreateIdeaButton();
        }

        this.appendIdeas();

        return this;
    },

    appendCreateIdeaButton() {
        this.$el.append($('<div class="dashboard-ideas-container idea-empty"><div class="dashboard-ideas-cell">0 Ideas: <a href="/plan">Create One</a></div></div>'));
    },

    appendToIdeaViews() {
        this.$el.find('.idea-empty').remove();
        this.collection.each((model) => this.idea_views.push(new recent_view({ model: model })));
    },

    appendIdeas() {
        this.idea_views.forEach((view) => {
            view.$el.hide();
            view.$el.fadeIn();
            this.$el.append(view.el);
        });
    }
});

var recent_view = Backbone.View.extend({
    tagName: "div",
    className: "dashboard-ideas-container",
    events:{
        "mouseenter": "showHover",
        "mouseleave": "hideHover",
    },
    template: _.template(`
        <div class="dashboard-ideas-cell cell-size-5">
            <% var profile_image = user.profile_image || "/images/avatar.jpg" %>
            <img src="<%= profile_image %>" alt="#" class="dashboard-tasks-img">
        </div>
        <div class="dashboard-ideas-cell cell-size-80">
            <p class="dashboard-ideas-text"><%= name %></p>
            <span class="dashboard-ideas-text small"><%= created_diff %></span>
        </div>
        <div class="dashboard-ideas-cell cell-size-15">
            <div class="dashboard-ideas-dropdown hidden idea-hover">
                <button type="button" class="button button-action" data-toggle="dropdown">
                    <i class="icon-add-circle"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                        <a href="/idea/write/<%= id %>">Write It</a>
                    </li>
                </ul>
            </div>
        </div>
    `),

    initialize() {
        this.render();
    },

    render() {
        this.formatModel();
        this.$el.append(this.template(this.model.attributes));

        return this;
    },

    formatModel() {
        this.model.attributes.created_diff = moment(this.model.get('created_at'))
            .format('MM/DD/YYYY h:mma');
    },

    showHover() {
        this.$el.find('.idea-hover').toggleClass('hidden');
    },

    hideHover() {
        this.$el.find('.idea-hover').toggleClass('hidden');
    },
});