launch.module.directive('richTextEditor', function($compile, $location, $templateCache) {
	var link = function (scope, element, attrs, ngModel) {
		tinymce.init({
			selector: '#' + element.attr('id'),
			plugins: [],
			toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"

		});
		//element.tinymce({
		//	// Location of TinyMCE script
		//	//script_url: 'http://resources.holycrap.ws/jscripts/tiny_mce/tiny_mce.js',

		//	// General options
		//	theme: 'simple',

		//	// Change from local directive scope -> 'parent' scope
		//	// Update Textarea and Trigger change event
		//	// you can also use handle_event_callback which fires more often
		//	onchange_callback: function(e) {
		//		if (this.isDirty()) {
		//			this.save();

		//			// tinymce inserts the value back to the textarea element, so we get the val from element (work's only for textareas)
		//			ngModel.$setViewValue(element.val());
		//			scope.$apply();

		//			return true;
		//		}

		//		return false;
		//	}
		//});
	};

	return {
		require: 'ngModel',
		link: link
	};
});