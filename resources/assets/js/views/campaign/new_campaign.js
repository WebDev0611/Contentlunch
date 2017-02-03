var NewCampaignView = Backbone.View.extend({
    d: null,
    dateFormat: 'YYYY-MM-DD',

    events: {
        "click #save-campaign-button": 'save_campaign'
    },

    initialize() {
        this.configureDatePicker();
    },

    configureDatePicker() {
        $('#start-date').datetimepicker({ format: this.dateFormat });
        $('#end-date').datetimepicker({ format: this.dateFormat );
    },

    show_saved(d) {
        this.$el.append(`
            <div class="col-md-12">
                <div class="alert alert-success" role="alert">
                    Saved: ${d.title}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
            </div>
        `);
    },

    update_campaign() {
        var view = this;
        console.log('running update');
        console.log(view.d);
        var form_data = {
            title: $('#campaign-title').val(),
            description: $('#campaign-description').val(),
            start_date: $('#start-date').val(),
            end_date: $('#end-date').val(),
            goals: $('#campaign-goals').val(),
            type: $('#campaign-types option:selected').val(),
            budget: $('#campaign-budget').val()
        };

        $.ajax({
            url: `/campaign/edit/${view.d.id}`,
            type: 'post',
            data: form_data,
            headers: getJsonHeader(),
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if (data.id) {
                    view.d = data;
                    view.show_saved(data);
                }
            }
        });
    },

    save_campaign() {
        var view = this;

        if (!view.d) {
            $.ajax({
                url: '/campaign/create',
                type: 'post',
                data: {
                    title: $('#campaign-title').val(),
                    description: $('#campaign-description').val(),
                    start_date: $('#start-date').val(),
                    end_date: $('#end-date').val(),
                    goals: $('#campaign-goals').val(),
                    type: $("#campaign-types option:selected").val(),
                    budget: $('#campaign-budget').val()
                },
                headers: getJsonHeader(),
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if(data.id){
                        view.d = data;
                        view.show_saved(data);
                    }
                }
            });
        } else {
            view.update_campaign();
        }
    }
});