<?php
return [
  'redirect_url' => 'https://dev.contentlaunch.com/api/add-connection',
  'acton' => [
    // Production keys
    'key' => 'B9XaHOER8YWiMZY_3eaTzyf2sd0a',
    'secret' => 'dGO3Nr69cZkzfp5FrbjlAbDFNHUa',
    'scope' => ['PRODUCTION'],
    'metric' => [
        'key' => 'views',
        'max' => '1000',
        'diversity_id' => 10
    ]
  ],
  // Use same api creds as google
  'blogger' => [
    'key' => '229252344182-6e0oa703c66350j3rgprhu1e6balqgn3.apps.googleusercontent.com',
    'secret' => 'bJ6EN7y6yDiG6oiTpVi3FfZn',
    'scope' => ['profile', 'https://www.googleapis.com/auth/blogger'],
    'metric' => [
        'key' => 'likes',
        'max' => '10',
        'diversity_id' => 4
    ]
  ],
  'dropbox' => [
    'key' => 'u2rsvtt8ugl938l',
    'secret' => 'xp39s1z1nib0pmt',
    'scope' => [],
    'metric' => [
        'key' => 'downloads',
        'max' => '10',
        'diversity_id' => 11
    ]
  ],
  // https://developers.facebook.com/docs/facebook-login/permissions/v2.0
  'facebook' => [
    'key' => '1514771092096173',
    'secret' => 'e7b592515d4d70912c8ae376e887f90b',
    'scope' => ['email', 'publish_actions'],
    'metric' => [
        'key' => 'likes',
        'max' => '10',
        'diversity_id' => 0
    ]
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
    'metric' => [
        'key' => 'downloads',
        'max' => '10',
        'diversity_id' => 11
    ]
  ],
  'google_plus' => [
    'key' => '229252344182-6e0oa703c66350j3rgprhu1e6balqgn3.apps.googleusercontent.com',
    'secret' => 'bJ6EN7y6yDiG6oiTpVi3FfZn',
    'scope' => ['profile', 'https://www.googleapis.com/auth/plus.login', 'https://www.googleapis.com/auth/plus.me'],
    'metric' => [
        'key' => 'likes',
        'max' => '10',
        'diversity_id' => 3
    ]
  ],
  'hubspot' => [
    'key' => 'b5badda1-cb0e-11e3-bd85-131c19601838',
    'secret' => '13aeed7d-b469-4a15-81a5-7d0058036075',
    // Content launch portal id, users should provide their own to connect to
    //'portalId' => '175282', 
    //'portalId' => '424894', // Jason's portal id
    'scope' => ['blog-rw', 'settings-rw', 'offline'],
    'metric' => [
        'key' => 'views',
        'max' => '10',
        'diversity_id' => 4
    ]
  ],
  'linkedin' => [
    'key' => '75uo3uakvtr7e1',
    'secret' => '4TFxfxhiA4P2rt5l',
    'scope' => ['rw_nus', 'r_basicprofile', 'r_network', 'w_messages', 'r_fullprofile', 'rw_groups', 'rw_company_admin'],
    'metric' => [
        'key' => 'likes',
        'max' => '10',
        'diversity_id' => 2
    ]
  ],
  'salesforce' => [
    'key' => '',
    'secret' => '',
    'scope' => ['api'],
  ],
  'scribe' => [
    'key' => 'scribe-524586291c334afe8d25cf4970150247',
  ],
  'slideshare' => [
      'key' => 'l5uz33i1',
      'secret' => 'NP33v7lc',
      'scope' => [],
      'metric' => [
          'key' => 'downloads',
          'max' => '10',
          'diversity_id' => 9
      ]
  ],
  'soundcloud' => [
    'key' => 'b7f6e48469239125b51f84a4ee83c845',
    'secret' => 'cac4165b86ee8c238cc1625c49352a95',
    'scope' => ['non-expiring'],
    'metric' => [
        'key' => 'views',
        'max' => '10',
        'diversity_id' => 8
    ]
  ],
  'tumblr' => [
    'key' => 'oRG7eGtygWOKVVqG8l7uQldeR0wtlzdxUCLkCeKl70WysDOsHq',
    'secret' => '8Iown7u7gwKCzq40Bn2n35lSunsyPFn7WEh6awqt4pilhgVGCh',
    'scope' => [],
    'metric' => [
        'key' => 'likes',
        'max' => '10',
        'diversity_id' => 5
    ]
  ],
  'twitter' => [
    'key' => 'sepmKBPSmyESKLOQ2ZnHJQ',
    'secret' => '8i6K65vmr6JEiU7VEvPkrFPmA89J7HWfpmmCxPxgo',
    'scope' => [],
    'metric' => [
        'key' => 'likes',
        'max' => '10',
        'diversity_id' => 1
    ]
  ],
  'wordpress' => [
    'key' => '45336',
    'secret' => 'OLeVQ155M3pB01MN1mn1HadKRCq466nrPfUqzhybmOVWqQZm1dlIMZNxQ4HCwE8M',
    'scope' => [],
    'metric' => [
        'key' => 'likes',
        'max' => '10',
        'diversity_id' => 4
    ]
  ],
  'traackr' => [
    // we're not doing any auth with Traackr (yet), so all we need is the API key
    'key' => '9bdcf55c7b277e26063a04d2dff577ba'
  ],
  'vimeo' => [
    'key' => 'eb1cc41f34259f58146c7f594b3965504c64ce95',
    'secret' => '390f70c3396dcfb3067caf005b1135ce3708f318',
    'scope' => ['public', 'private', 'upload', 'edit'],
    'metric' => [
        'key' => 'views',
        'max' => '10',
        'diversity_id' => 6
    ]
  ],
  // Use same api creds as google
  'youtube' => [
    'key' => '229252344182-6e0oa703c66350j3rgprhu1e6balqgn3.apps.googleusercontent.com',
    'secret' => 'bJ6EN7y6yDiG6oiTpVi3FfZn',
    'scope' => ['profile', 'https://www.googleapis.com/auth/youtube', 'https://www.googleapis.com/auth/youtube.upload'],
    'metric' => [
        'key' => 'comments',
        'max' => '10',
        'diversity_id' => 7
    ]
  ],
  'balanced' => [
    //'key' => 'ak-prod-1ZNSGbwQj3yQKTWusJrdm07KyU5gtvPcs' // Real production marketplace 
    'key' => 'ak-test-2nTDcVjW9XkNMAtdh5xLIMMPeHaliy5Aa'
  ]
];
