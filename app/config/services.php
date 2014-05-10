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

// For testing, providers allow for different callback urls (localhost, must use https, must not contain port)
// Not sure yet how to elegantly test across all providers from the same url
// The developer could edit his host file to point for example https://contentlaunch.com to his local instance
// For now, the callback_domain is where the provider will redirect to
return [
  'dropbox' => [
    'key' => '379fuhe952gx0ho',
    'secret' => '56b7243xrr4ww6u',
    'scope' => [],
    'callback_domain' => 'http://localhost:8080'
  ],
  // https://developers.facebook.com/docs/facebook-login/permissions/v2.0
  'facebook' => [
    'key' => '207404499314444',
    'secret' => 'aa020dedfa881a0938ad157e48045c2b',
    'scope' => ['email'],
    'callback_domain' => 'http://local.contentlaunch.com'
  ],
  // https://developers.google.com/+/api/oauth
  'google' => [
    'key' => '105997751893-s34v4dmp7rauav72146qigpg94jnu83a.apps.googleusercontent.com',
    'secret' => 'qSM5V2SgkYx2iUMJy0oBDo_w',
    'scope' => ['userinfo_email', 'userinfo_profile'],
    'callback_domain' => 'http://localhost:8080'
  ],
  'hubspot' => [
    'key' => 'b5badda1-cb0e-11e3-bd85-131c19601838',
    'secret' => '13aeed7d-b469-4a15-81a5-7d0058036075',
    'scope' => [],
    'callback_domain' => 'http://local.contentlaunch.com'
  ],
  'linkedin' => [
    'key' => '75v9b2qqxfhxrf',
    'secret' => 'EpLWyw4wv73LyW57',
    'scope' => ['r_basicprofile'],
    'callback_domain' => 'http://local.contentlaunch.com'
  ],
  'salesforce' => [
    'key' => '3MVG9xOCXq4ID1uGOu_IgyQS3vy6F.y8t87aMawqYJRUJPhv1n1efppn5h4B0_ZxPDFf8WPoEdq9gWVj4W0rp',
    'secret' => '8067118373531747124',
    'scope' => ['api'],
    'callback_domain' => 'http://localhost:8080'
  ],
  'twitter' => [
    'key' => 'E6YObyKe5gqycy1vJslG9dl7s',
    'secret' => 'L6nB51eQxiIyRy6cOnlGvCXoKPRnQNzb4lQQJvNw0zeW5uLp40',
    'scope' => [],
    'callback_domain' => 'http://local.contentlaunch.com'
  ],
  'wordpress' => [
    'key' => '34902',
    'secret' => 'enngUSOLGj7kZCb6hbNH7vk3CgUjoi3pdBMX3Rkckqmx6tJGdujLCyTSCnRNt5ld',
    'scope' => [],
    'callback_domain' => 'http://local.contentlaunch.com'
  ]
];