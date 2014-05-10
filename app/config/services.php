<?php



/*
  case 'HUBSPOT':
          url = 'https://app.hubspot.com/auth/authenticate/?client_id=' + launch.config.HUBSPOT_API_KEY +
            '&portalId=' + '175282' + // TODO: HOW DO WE USE THIS PORTAL ID???
            '&redirect_uri=' + encodeURI('http://local.contentlaunch.cself.loggedInUser.account.idom/account/connections');
          break;
        case 'WORDPRESS':
          url = 'https://public-api.wordpress.com/oauth2/authorize?client_id=' + launch.config.WORDPRESS_API_KEY +
            '&redirect_uri=' + encodeURIComponent('http://local.contentlaunch.com/account/connections') + '&response_type=code';
*/

return [
  'dropbox' => [
    'key' => '379fuhe952gx0ho',
    'secret' => '56b7243xrr4ww6u',
    'scope' => []
  ],
  'facebook' => [
    'key' => '207404499314444',
    'secret' => 'aa020dedfa881a0938ad157e48045c2b',
    // https://developers.facebook.com/docs/facebook-login/permissions/v2.0
    'scope' => ['email'],
  ],
  'hubspot' => [
    'key' => 'b5badda1-cb0e-11e3-bd85-131c19601838',
    'secret' => '13aeed7d-b469-4a15-81a5-7d0058036075',
    'scope' => []
  ],
  'linkedin' => [
    'key' => '75v9b2qqxfhxrf',
    'secret' => 'EpLWyw4wv73LyW57',
    'scope' => ['r_basicprofile']
  ],
  'wordpress' => [
    'key' => '34902',
    'secret' => 'enngUSOLGj7kZCb6hbNH7vk3CgUjoi3pdBMX3Rkckqmx6tJGdujLCyTSCnRNt5ld',
    'scope' => [],
  ]
];