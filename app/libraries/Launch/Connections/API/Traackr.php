<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use Traackr\Influencers;
use Traackr\TraackrApi;

/**
 * @see: http://api.docs.traackr.com/traackr_1_0
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
        return Influencers::search([
            'keywords' => $keywords
        ])['influencers'];
    }

}