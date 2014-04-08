launch.module.directive('richTextEditor', function($compile, $location, $templateCache) {
	var link = function (scope, element, attrs, ngModel) {
		tinymce.init({
			selector: '#' + element.attr('id'),
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
	};

	return {
		require: 'ngModel',
		link: link
	};
});