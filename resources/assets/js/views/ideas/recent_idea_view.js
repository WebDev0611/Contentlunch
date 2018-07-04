'use strict';

var recent_idea_view = Backbone.View.extend({
    tagName: "div",
    className: "dashboard-ideas-container",
    events:{
        "mouseenter": "showHover",
        "mouseleave": "hideHover",
    },
    template: _.template(`
        <div class="dashboard-ideas-cell cell-size-5">
            <div class="dashboard-tasks-img-wrapper">
                <% var profile_image = user.profile_image || "/images/cl-avatar2.png" %>
                <img src="<%= profile_image %>" alt="#" class="dashboard-tasks-img">
            </div>
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
                        <a href="/idea/<%= id %>/write">Write It</a>
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
        this.model.attributes.created_diff = moment.utc(this.model.get('created_at'))
            .local().format('MM/DD/YYYY h:mma');
    },

    showHover() {
        this.$el.find('.idea-hover').toggleClass('hidden');
    },

    hideHover() {
        this.$el.find('.idea-hover').toggleClass('hidden');
    },
});