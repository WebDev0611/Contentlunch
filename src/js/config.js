launch.config = {
	DEBUG_MODE: true,
	USER_PHOTO_FILE_TYPES: ['image/gif', 'image/png', 'image/jpeg', 'image/bmp'],
	MIN_PASSWORD_LENGTH: 8,
	EMAIL_ADDRESS_REGEX: /^([a-zA-Z0-9_!#%='`\-\.\$~\&\*\+\-\/\?\^\{\|\}]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/,
	CONTENT_TYPES: [
		{ name: 'audio', title: 'Audio' },
		{ name: 'blog_post', title: 'Blog Post' },
		{ name: 'case_study', title: 'Case Study' },
		{ name: 'ebook', title: 'Ebook' },
		{ name: 'email', title: 'Email' },
		{ name: 'facebook_post', title: 'Facebook Post' },
		{ name: 'google_drive', title: 'Google Drive' },
		{ name: 'landing_page', title: 'Landing Page' },
		{ name: 'linkedin', title: 'LinkedIn' },
		{ name: 'photo', title: 'Photo' },
		{ name: 'salesforce_asset', title: 'Salesforce Asset' },
		{ name: 'twitter', title: 'Twitter' },
		{ name: 'video', title: 'Video' },
		{ name: 'whitepaper', title: 'Whitepaper' }
	],
	CONNECTION_PROVIDERS: [
		{ name: 'hubspot', title: 'Hubspot' },
		{ name: 'linkedin', title: 'LinkedIn' },
		{ name: 'wordpress', title: 'Wordpress' }
	],
	LINKEDIN_API_KEY: '757vbb06ghwe32',
	LINKEDIN_API_SECRET: 'V9iMuEFHlfImfOJ0',
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
		toolbar3: 'cut copy paste | undo redo | searchreplace | preview print',

		menubar: false,
		toolbar_items_size: 'small'
	}
};