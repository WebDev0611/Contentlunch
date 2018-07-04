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
            <% var avatar = user.profile_image || '/images/cl-avatar2.png'; %>
            <img src="<%= avatar %>" alt="#" class="plan-ideas-img">
        </div>
        <div class="plan-ideas-cell cell-size-35">
            <a href="/idea/<%= id %>">
                <h5 class="plan-ideas-title">
                    <%= name %>
                </h5>
            </a>
            <span class="plan-ideas-text"><%= text %></span>
        </div>

        <div class="plan-ideas-cell cell-size-25">
            <span class="plan-ideas-text"><%= created_diff.toUpperCase() %></span>
        </div>
        <div class="plan-ideas-cell cell-size-25">
            <span class="plan-ideas-text">UPDATED: <%= updated_diff.toUpperCase() %></span>
        </div>

        <div class="plan-ideas-cell cell-size-10">
            <div class="plan-ideas-dropdown">
                <button type="button" class="button button-action pull-right" data-toggle="dropdown">
                    <i class="icon-add-circle"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-right">
                    <li id="write-it-btn">
                        <a href="javascript:;">Write It</a>
                    </li>

                    <li id="edit-it-btn">
                        <a href="javascript:;">Edit It</a>
                    </li>

                    <li id="park-it-btn">
                        <a href="javascript:;">Park It</a>
                    </li>

                    <li id="unpark-it-btn">
                        <a href="javascript:;">Unpark It</a>
                    </li>
                </ul>
            </div>
        </div>
    `),

    initialize() {
        this.render();
    },

    render() {
        this.$el.html(this.template(this.model.attributes));
        const status = this.model.get('status');

        if (status == 'parked') {
            this.$el.find('#park-it-btn').hide();
        } else {
            this.$el.find('#unpark-it-btn').hide();
            this.$el.find('#park-it-btn').show();
        }

        return this;
    },

    edit() {
        window.location.href = '/idea/' + this.model.get('id');
    },

    write() {
        window.location.href = '/idea/' + this.model.get('id') + '/write/';
    },

    park() {
        return $.post('/idea/' + this.model.get('id') + '/park').then(res => location.reload());
    },

    activate() {
        return $.post('/idea/' + this.model.get('id') + '/activate').then(res => location.reload());
    }
});

