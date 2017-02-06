'use strict';

var CampaignCollaboratorView = Backbone.View.extend({
    template: _.template(`
        <a href="#">
            <div class="user-avatar">
                <img src="/images/cl-avatar2.png" alt="#">
            </div>
            <p class="title">Jason Simmons</p>
            <p class="email">jasonsimm@google.com</p>
        </a>
    `),

    tagName: 'li',

    render() {
        this.$el.html(this.template());
        // this.$el.html(this.template(this.model.attributes));

        return this;
    }
});