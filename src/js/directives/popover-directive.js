launch.module.directive('menuPopover', function($compile, $location, $templateCache) {
	return {
		restrict: 'A',
		scope: {
			currentMenu: '=menuItems'
		},
		link: function(scope, element, attrs) {
			var popOverContent = $compile($templateCache.get('popover-menu.html'))(scope);
			var placement = element.data('placement') || 'top';
			var trigger = element.data('trigger') || 'click';
			var menu = Boolean(element.data('menu'));

			scope.navigate = function(url) {
				$location.url(scope.$parent.navigate(url));
			};

			var options = {
				content: popOverContent,
				placement: placement,
				trigger: trigger,
				html: true,
				delay: { hide: 250 },
				container: element
			};

			if (menu && (trigger === 'hover')) {
				$(element).popover(options).on('mouseenter', function(e) {
						var self = $(this);

						self.data('hoveringPopover', true);

						if (self.data('waitingForPopoverTO')) {
							e.stopImmediatePropagation();
						}
					})
					.on('mouseleave', function(e) {
						e.stopImmediatePropagation();

						var self = $(this);

						if (self.data('forceHidePopover')) {
							self.data('forceHidePopover', false);
							return true;
						}

						clearTimeout(self.data('popoverTO'));

						self.data('hoveringPopover', false);
						self.data('waitingForPopoverTO', true);
						self.data('popoverTO', setTimeout(function() {
							if (!self.data('hoveringPopover')) {
								self.data('forceHidePopover', true);
								self.data('waitingForPopoverTO', false);
								self.popover('hide');
							}
						}, 1500));

						return false;
					});
			} else {
				$(element).popover(options);
			}
		}
	};
});