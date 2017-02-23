<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;

class TrendsController extends Controller
{
    public function trending(Request $request)
    {
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

        function get_data($t)
        {
            $api_url = 'https://api.rightrelevance.com/v2/articles/search?';

            $params = array(
                'days' => '365',
                'rgroup' => 'large',
                'query' => $t,
                'access_token' => getenv('RIGHTRELEVANCE_TOKEN'),
            );

            $api_url .= http_build_query($params);

            $client = new Client();
            $res = $client->request('GET', $api_url);

            $data_body = $res->getBody();

            return json_decode($data_body->getContents());
        }

        if (empty(unserialize($topic_cache))) {
            $output = get_data($topic);
            Redis::set($topic_key, serialize($output));
            Redis::expire($topic_key, 60 * 20); //set cache for 10 min
        } else {
            $output = unserialize($topic_cache);
        }

        return response()->json($output);
    }
}
