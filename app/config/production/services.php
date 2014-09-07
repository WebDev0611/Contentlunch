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
    'key' => '229252344182-6e0oa703c66350j3rgprhu1e6balqgn3.apps.googleusercontent.com',
    'secret' => 'bJ6EN7y6yDiG6oiTpVi3FfZn',
    'scope' => ['profile', 'https://www.googleapis.com/auth/blogger'],
  ],
  'dropbox' => [
    'key' => 'u2rsvtt8ugl938l',
    'secret' => 'xp39s1z1nib0pmt',
    'scope' => [],
  ],
  // https://developers.facebook.com/docs/facebook-login/permissions/v2.0
  'facebook' => [
    'key' => '1514771092096173',
    'secret' => 'e7b592515d4d70912c8ae376e887f90b',
    'scope' => ['email', 'publish_actions'],
  ],
  // https://developers.google.com/+/api/oauth
  'google' => [
    'key' => '229252344182-6e0oa703c66350j3rgprhu1e6balqgn3.apps.googleusercontent.com',
    'secret' => 'bJ6EN7y6yDiG6oiTpVi3FfZn',
    'scope' => ['userinfo_email', 'userinfo_profile', 'https://www.googleapis.com/auth/youtube'],
  ],
  'google_drive' => [
    'key' => '229252344182-6e0oa703c66350j3rgprhu1e6balqgn3.apps.googleusercontent.com',
    'secret' => 'bJ6EN7y6yDiG6oiTpVi3FfZn',
    'scope' => ['profile', 'https://www.googleapis.com/auth/drive'],
  ],
  'google_plus' => [
    'key' => '229252344182-6e0oa703c66350j3rgprhu1e6balqgn3.apps.googleusercontent.com',
    'secret' => 'bJ6EN7y6yDiG6oiTpVi3FfZn',
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
    'key' => '75uo3uakvtr7e1',
    'secret' => '4TFxfxhiA4P2rt5l',
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
    'key' => 'b7f6e48469239125b51f84a4ee83c845',
    'secret' => 'cac4165b86ee8c238cc1625c49352a95',
    'scope' => ['non-expiring'],
  ],
  'tumblr' => [
    'key' => 'oRG7eGtygWOKVVqG8l7uQldeR0wtlzdxUCLkCeKl70WysDOsHq',
    'secret' => '8Iown7u7gwKCzq40Bn2n35lSunsyPFn7WEh6awqt4pilhgVGCh',
    'scope' => [],
  ],
  'twitter' => [
    'key' => 'sepmKBPSmyESKLOQ2ZnHJQ',
    'secret' => '8i6K65vmr6JEiU7VEvPkrFPmA89J7HWfpmmCxPxgo',
    'scope' => [],
  ],
  'wordpress' => [
    'key' => '36719',
    'secret' => 'kfANNW247Psf1aX4ROWtvtnG5sSU9jw8fdWmdXJVKej5ROSekrKYm4RzTGh4Ueg8',
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
    'key' => '229252344182-6e0oa703c66350j3rgprhu1e6balqgn3.apps.googleusercontent.com',
    'secret' => 'bJ6EN7y6yDiG6oiTpVi3FfZn',
    'scope' => ['profile', 'https://www.googleapis.com/auth/youtube', 'https://www.googleapis.com/auth/youtube.upload'],
  ]
];
