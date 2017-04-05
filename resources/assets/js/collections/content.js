    var content_collection = Backbone.Collection.extend({
        url: '/content/my',
        model: content_model,

        last30Days: function () {
            let filtered = this.filter(function (content) {
                return moment(content.get("created_at")).isAfter(moment().subtract(1, 'months'));
            });
            return new content_collection(filtered);
        }
    });