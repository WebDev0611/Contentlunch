'use strict';

var IdeasCollaboratorModalView = Backbone.View.extend({
    template: _.template(`
        <label class="checkbox-tag">
            <input type="checkbox" data-id='<%= id %>'
                <% if (is_collaborator) { %>
                    checked=checked
                <% } %>
                <% if (is_logged_user) { %>
                    disabled="disabled"
                <% } %>>
            <span><%= name %></span>
        </label>
    `),

    tagName: 'div',

    render() {
        this.$el.html(this.template(this.model.toJSON()));
        return this;
    },
});
