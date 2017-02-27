<?php

namespace App\Http\Controllers;

use App\Idea;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class TopicsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->cant('searchTopics', Idea::class)) {
            return response()->json([
                'data' => 'You exceeded your topic searches limit.'
            ], 403);
        }
        Auth::user()->addToLimit('topic_search');

        $keyword = $request->input('keyword');

        //set up the hash key
        $topic_cache_key = 'topics:';
        if (empty($keyword)) {
            $topic_cache_key .= '_';
        } else {
            $topic_cache_key .= urlencode($keyword);
        }

        // Get the cache
        $topic_cache = Redis::get($topic_cache_key);
        $results = [];

        if (empty(unserialize($topic_cache))) {
            $results = $this->get_data($keyword);

            Redis::set($topic_cache_key, serialize($results));
            Redis::expire($topic_cache_key, 60 * 20); //set cache for 10 min
        } else {
            $results = unserialize($topic_cache);
        }

        return response()->json($results);
    }

    private function get_data($t, $length_flag = 'short')
    {
        $api_url = 'http://api3.wordtracker.com/search?';
        $keywords = trim($t);

        $request_string = $api_url.'app_id='.getenv('WORDTRACKER_ID').'&app_key='.getenv('WORDTRACKER_KEY');
        $request_string .= '&limit='. 16;
        $request_string .= '&keywords[]='.urlencode($keywords);

        $client = new Client();
        $res = $client->request('GET', $request_string);

        $data_body = $res->getBody();

        return json_decode($data_body->getContents());
    }
}
