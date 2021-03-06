<?php

namespace App\Http\Controllers;

use App\Idea;
use App\Limit;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class TrendsController extends Controller
{
    public function trending(Request $request)
    {
        if (Auth::user()->cant('searchTrends', Idea::class)) {
            return response()->json(['data' => Limit::feedbackMessage('trend_search')], 403);
        }
        Auth::user()->addToLimit('trend_search');

        $topic = $request->input('topic');
        if (empty($topic)) {
            echo json_encode([]);
            exit;
        }
        $topic_key = 'trends:';

        if (empty($topic)) {
            $topic_key .= '_';
        } else {
            $topic_key .= str_replace(' ', ',', $topic);
        }

        $topic_cache = Redis::get($topic_key);
        $output = '';

        if (empty(unserialize($topic_cache))) {
            $output = $this->getData($topic);
            Redis::set($topic_key, serialize($output));
            Redis::expire($topic_key, 60 * 20); //set cache for 10 min
        } else {
            $output = unserialize($topic_cache);
        }

        return response()->json($output);
    }

    protected function getData($topic)
    {
        $api_url = 'https://api.rightrelevance.com/v2/articles/search?';

        $params = [
            'days' => '10000',
            'query' => $topic,
            //'min_score' => 40,
            //'max_score' => 100,
            //'orderby' => 'relevance',
            'start' => 0,
            'rows' => 40,
            'access_token' => getenv('RIGHTRELEVANCE_TOKEN'),
        ];

        $api_url .= http_build_query($params);

        $client = new Client();
        $res = $client->request('GET', $api_url);

        $data_body = $res->getBody();

        return json_decode($data_body->getContents());
    }
}
