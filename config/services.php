<?php
//use Illuminate\Support\Facades\Session;

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_PUBLISHABLE_KEY'),
        'secret' => env('STRIPE_SECRET_KEY'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_APP_ID'),// (Session::get('qebotUser') ? env('FACEBOOK_APP_ID_QEBOT') : env('FACEBOOK_APP_ID') ),
        'client_secret' =>  env('FACEBOOK_APP_SECRET'), //(Session::get('qebotUser') ? env('FACEBOOK_APP_SECRET_QEBOT') : env('FACEBOOK_APP_SECRET') ),
        'redirect' => env('FACEBOOK_REDIRECT')
    ],

    'wordpress' => [
        'client_id' => env('WORDPRESS_CLIENT_ID'),
        'client_secret' => env('WORDPRESS_CLIENT_SECRET'),
        'redirect' => env('WORDPRESS_REDIRECT')
    ],

    'hubspot' => [
        'client_id' => env('HUBSPOT_CLIENT_ID'),
        'client_secret' => env('HUBSPOT_CLIENT_SECRET'),
        'redirect' => env('HUBSPOT_REDIRECT')
    ],

    'mailchimp' => [
        'client_id' => env('MAILCHIMP_CLIENT_ID'),
        'client_secret' => env('MAILCHIMP_CLIENT_SECRET'),
        'redirect' => env('MAILCHIMP_REDIRECT')
    ],

    'linkedin' => [
        'client_id' => env('LINKEDIN_CLIENT_ID'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        'redirect' => env('LINKEDIN_REDIRECT')
    ],

    'dropbox' => [
        'client_id' => env('DROPBOX_CLIENT_ID'),
        'client_secret' => env('DROPBOX_CLIENT_SECRET'),
        'redirect' => env('DROPBOX_REDIRECT')
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET')
    ],

    'google-drive' => [
        'redirect' => env('GOOGLE_REDIRECT_DRIVE')
    ],

    'google-analytics' => [
        'redirect' => env('GOOGLE_REDIRECT_ANALYTICS')
    ],
];
