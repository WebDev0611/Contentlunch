<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use Traackr\Influencers;
use Traackr\TraackrApi;

/**
 * @see: http://api.docs.traackr.com/traackr_1_0
 * @see: http://traackr.github.io/traackr-api-docs/
 */
class Traackr {

    protected $configKey = 'services.traackr';

    public function __construct()
    {
        $this->config = Config::get($this->configKey);
        TraackrApi::setApiKey($this->config['key']);
    }

    public function searchInfluencers($keywords)
    {
        // get all the influencers returned by keywords (keywords is comma separated string)
        $influencers = Influencers::search([
            'keywords' => $keywords
            // default limit is something like 25?
        ])['influencers'];

        if (count($influencers) < 1) {
            return [];
        }

        // we need to get relevance (as compared to keywords) as it's the only thing we can't get from show
        $relevances = [];
        foreach ($influencers as $influencer) {
            $relevances[$influencer['uid']] = $influencer['relevance'];
        }

        // get a comma delimited list of all the user IDs returned in the last query
        $influencerIDs = array_map(function ($influencer) {
            return $influencer['uid'];
        }, $influencers);

        // get all the FULL influencer profiles for all of the influencers returned (need to do this to get social footprint stuff)
        // this only works with <= 50 profiles
        $influencers = Influencers::show($influencerIDs, ['with_channels' => true])['influencer'];

        // array_values gets rid of uid-as-key to make it a nice JSON array
        return array_values(array_map(function ($influencer) use ($relevances) {
            // trim the response to save on payload size

            $influencer['social'] = [];
            foreach($influencer['channels'] as $i => $channel) {
                // only need Twitter and LinkedIn
                if ($channel['root_domain'] == 'twitter' || $channel['root_domain'] == 'linkedin') {
                    $influencer['social'][] = [
                        'type' => $channel['root_domain'],
                        'url'  => $channel['url'],
                    ];
                }
            }

            unset(
                $influencer['description'],
                $influencer['title'],
                $influencer['location'],
                $influencer['iso_location'],
                $influencer['email'],
                $influencer['gender'],
                $influencer['thumbnail_url'],
                $influencer['avatar']['large'],
                $influencer['avatar']['medium'],
                $influencer['channels']
            );

            $influencer['relevance'] = $relevances[$influencer['uid']];

            return $influencer;
        }, $influencers));
    }

}