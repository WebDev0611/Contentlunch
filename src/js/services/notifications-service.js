launch.module.factory('NotificationService', function() {
	var self = this;

	self.defaultTimeout = 4000;
	self.notify = function(title, msg, timeout, type) {
		if (!launch.utils.isBlank(title) || launch.utils.isBlank(msg)) {
			return $.pnotify({
				title: title,
				text: msg,
				width: '500px',
				delay: parseInt(timeout) || self.defaultTimeout,
				type: (launch.utils.isBlank(type) ? 'info' : type),
				before_open: function(pnotify) {

					pnotify.css({
						top: ($(window).height() / 2) - (pnotify.height() / 2),
						left: ($(window).width() / 2) - (pnotify.width() / 2)
					});
				}
			});
		}

		return null;
	};

	return {
		notify: function(title, msg, timeout) {
			return self.notify(title, msg, timeout, 'notice');
		},
		success: function(title, msg, timeout) {
			return self.notify(title, msg, timeout, 'success');
		},
		error: function(title, msg, timeout) {
            //Remove all other popups if there are multiples
            $(".ui-pnotify").hide().find(".ui-pnotify-closer").trigger("click");

            // Enabling generic messages unless debug=true
            //if(window.debug || location.search.includes("debug=true")){
                msg = msg;
            //}else{
              //  msg = "Looks like something went wrong. <br/>The ContentLaunch support team has been notified."
            //}
			return self.notify(title, msg, timeout, 'error');
		},
		info: function(title, msg, timeout) {
			return self.notify(title, msg, timeout, 'info');
		}
	};
});