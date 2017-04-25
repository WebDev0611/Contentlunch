<?php

class ProviderTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        DB::table('providers')->truncate();
        $this->insertProviders();

        $this->enableForeignKeys();
    }

    public function insertProviders()
    {
        DB::table('providers')->insert([
            [
                'name' => 'Wordpress',
                'slug' => 'wordpress',
                'type' => 'blog',
                'class_name' => 'WordPressAPI'
            ],
            [
                'name' => 'Linkedin',
                'slug' => 'linkedin',
                'type' => 'website',
                'class_name' => 'LinkedInAPI'
            ],
            [
                'name' => 'Hubspot',
                'slug' => 'hubspot',
                'type' => 'website',
                'class_name' => 'HubspotAPI'
            ],
            [
                'name' => 'Act-on',
                'slug' => 'act-on',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Facebook',
                'slug' => 'facebook',
                'type' => 'website',
                'class_name' => 'FacebookAPI'
            ],
            [
                'name' => 'Twitter',
                'slug' => 'twitter',
                'type' => 'website',
                'class_name' => 'TwitterAPI'
            ],
            [
                'name' => 'YouTube',
                'slug' => 'youtube',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Outbrain',
                'slug' => 'outbrain',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Dropbox',
                'slug' => 'dropbox',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Google Drive',
                'slug' => 'google-drive',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Slideshare',
                'slug' => 'slideshare',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Tumblr',
                'slug' => 'tumblr',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Google+',
                'slug' => 'google+',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Soundcloud',
                'slug' => 'soundcloud',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Blogger',
                'slug' => 'blogger',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Google Analytics',
                'slug' => 'google-analytics',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Drupal',
                'slug' => 'drupal',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Salesforce',
                'slug' => 'salesforce',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Nimble',
                'slug' => 'nimble',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Hootsuite',
                'slug' => 'hootsuite',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'ReadyTalk',
                'slug' => 'readytalk',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Constant Contact',
                'slug' => 'constant-contact',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Mailchimp',
                'slug' => 'mailchimp',
                'type' => 'website',
                'class_name' => 'MailchimpAPI'
            ],
            [
                'name' => 'Exact Target',
                'slug' => 'exact-target',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Vertical Response',
                'slug' => 'vertical-response',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Marketo',
                'slug' => 'Marketo',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Eloqua',
                'slug' => 'Eloqua',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Joomla',
                'slug' => 'joomla',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'SmartShoot',
                'slug' => 'smartshoot',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'SharpSpring',
                'slug' => 'sharpspring',
                'type' => 'website',
                'class_name' => ''
            ],
            [
                'name' => 'Orbtr',
                'slug' => 'orbtr',
                'type' => 'website',
                'class_name' => ''
            ]
        ]);
    }
}
