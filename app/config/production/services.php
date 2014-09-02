<?php
return [
  'redirect_url' => 'https://app.contentlaunch.com/api/add-connection',
  'acton' => [
    // Production keys
    'key' => 'B9XaHOER8YWiMZY_3eaTzyf2sd0a',
    'secret' => 'dGO3Nr69cZkzfp5FrbjlAbDFNHUa',
    'scope' => ['PRODUCTION']
  ],
  // Use same api creds as google
  'blogger' => [
    'key' => '',
    'secret' => '',
    'scope' => ['profile', 'https://www.googleapis.com/auth/blogger'],
  ],
  'dropbox' => [
    'key' => '',
    'secret' => '',
    'scope' => [],
  ],
  // https://developers.facebook.com/docs/facebook-login/permissions/v2.0
  'facebook' => [
    'key' => '',
    'secret' => '',
    'scope' => ['email', 'publish_actions'],
  ],
  // https://developers.google.com/+/api/oauth
  'google' => [
    'key' => '',
    'secret' => '',
    'scope' => ['userinfo_email', 'userinfo_profile', 'https://www.googleapis.com/auth/youtube'],
  ],
  'google_drive' => [
    'key' => '',
    'secret' => '',
    'scope' => ['profile', 'https://www.googleapis.com/auth/drive'],
  ],
  'google_plus' => [
    'key' => '',
    'secret' => '',
    'scope' => ['profile', 'https://www.googleapis.com/auth/plus.login', 'https://www.googleapis.com/auth/plus.me'],
  ],
  'hubspot' => [
    'key' => '',
    'secret' => '',
    // Content launch portal id, users should provide their own to connect to
    //'portalId' => '175282', 
    //'portalId' => '424894', // Jason's portal id
    'scope' => ['blog-rw', 'settings-rw', 'offline'],
  ],
  'linkedin' => [
    'key' => '',
    'secret' => '',
    'scope' => ['rw_nus', 'r_basicprofile', 'r_network', 'w_messages', 'r_fullprofile', 'rw_groups'],
  ],
  'salesforce' => [
    'key' => '',
    'secret' => '',
    'scope' => ['api'],
  ],
  'scribe' => [
    'key' => 'scribe-524586291c334afe8d25cf4970150247',
  ],
  'soundcloud' => [
    'key' => '',
    'secret' => '',
    'scope' => ['non-expiring'],
  ],
  'tumblr' => [
    'key' => '',
    'secret' => '',
    'scope' => [],
  ],
  'twitter' => [
    'key' => '',
    'secret' => '',
    'scope' => [],
  ],
  'wordpress' => [
    'key' => '',
    'secret' => '',
    'scope' => [],
  ],
  'traackr' => [
    // we're not doing any auth with Traackr (yet), so all we need is the API key
    'key' => '9bdcf55c7b277e26063a04d2dff577ba'
  ],
  'vimeo' => [
    'key' => '',
    'secret' => '',
    'scope' => ['public', 'private', 'upload', 'edit'],
  ],
  // Use same api creds as google
  'youtube' => [
    'key' => '',
    'secret' => '',
    'scope' => ['profile', 'https://www.googleapis.com/auth/youtube', 'https://www.googleapis.com/auth/youtube.upload'],
  ]
];
