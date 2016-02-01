launch.module.factory('AnalyticsService', ["$window", function($window) {
    var self = this;



    return {
        identify: function(userId) {
            // TODO - integrate with our eventual analytics service, mixpanel?
        },

        trackEvent: function(eventName, properties) {
            // TODO - integrate with our eventual analytics service, mixpanel?

        },

        trackConversion: function(properties) {
            $window.google_trackConversion(properties);
            //{
            //    google_conversion_id: 1027851638,
            //        google_conversion_label: 'kFiJCIGnvVcQ9oqP6gM',
            //    google_conversion_language: "en",
            //    google_conversion_format: "2",
            //    google_conversion_color: "ffffff",
            //    google_remarketing_only: false
            //}
        }

    };
}]);