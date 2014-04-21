﻿launch.module.directive('richTextEditor', function ($window, $compile, $location, $templateCache) {
	var link = function(scope, element, attrs, ngModel) {
		var id = '#' + element.attr('id');

		$window.setTimeout(function() {
			tinymce.init({
				selector: id,
				plugins: [
					'advlist autolink link image lists charmap print preview anchor',
					'searchreplace wordcount visualblocks visualchars code media',
					'table contextmenu emoticons textcolor paste'
				],

				// FONT TOOLBAR
				toolbar1: 'fontselect fontsizeselect | bold italic underline strikethrough | forecolor backcolor | link unlink anchor image media code | subscript superscript | charmap emoticons',

				// PARAGRAPH TOOLBAR
				toolbar2: 'formatselect | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | table | removeformat | visualchars visualblocks',

				// DOCUMENT TOOLBAR
				toolbar3: 'cut copy paste | undo redo | searchreplace | preview print',

				menubar: false,
				toolbar_items_size: 'small'
			});

			element.on('$destroy', function() {
				tinymce.remove(id);
			});
		}, 0);
	};

	return {
		require: 'ngModel',
		link: link
	};
});