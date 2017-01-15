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

        if (this.model.get('status') == 'parked') {
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
        window.location.href = '/idea/write/' + this.model.get('id');
    },

    park() {
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

    activate() {
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

