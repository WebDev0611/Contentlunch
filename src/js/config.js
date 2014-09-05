﻿launch.config = {
	DEBUG_MODE: false,
	USER_PHOTO_FILE_TYPES: ['image/gif', 'image/png', 'image/jpeg', 'image/bmp'],
	SLIDE_DECK_FILE_TYPES: ['ppt', 'pptx', 'pdf', 'key', 'keynote', 'pez'],
	MIN_PASSWORD_LENGTH: 8,
	EMAIL_ADDRESS_REGEX: /^([a-zA-Z0-9_!#%='`\-\.\$~\&\*\+\-\/\?\^\{\|\}]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/,
	SEO_PROVIDERS: [
		{ name: 'inbound_writer', title: 'Inbound Writer Integration' },
		{ name: 'yoast', title: 'Yoast' },
		{ name: 'all_in_one_seo', title: 'All in One SEO' },
		{ name: 'genesis_theme_seo', title: 'Genesis Theme SEO' },
		{ name: 'seo-ultimate', title: 'SEO Ultimate' },
		{ name: 'sales_power', title: 'Sales Power' },
		{ name: 'sales_machine', title: 'Sales Machine' },
		{ name: 'nsm_better_meta', title: 'NSM Better Meta' },
		{ name: 'lg_better_meta', title: 'LG Better Meta' },
		{ name: 'metatag_drupal', title: 'Metatag (Drupal)' }
	],
	TINY_MCE_SETTINGS: {
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
		toolbar3: 'undo redo | searchreplace | preview print',

		menubar: false,
		
		toolbar_items_size: 'small'
	}
};