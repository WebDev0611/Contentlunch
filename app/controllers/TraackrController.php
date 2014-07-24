<?php

use Launch\Connections\API\Traackr;

class TraackrController extends \BaseController {

    /**
     * Display a listing of the resource.
     * GET /api/traackr/search-influencers
     *
     * @return Response
     */
    public function searchInfluencers()
    {
        $keywords = Input::get('keywords');

        $traackr = new Traackr();
        $influencers = $traackr->searchInfluencers($keywords);

        return $influencers;
    }

}