<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use GuzzleHttp\Client;

use Illuminate\Support\Facades\Redis;

class TrendsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function trending(Request $request)
    {
        $topic = $request->input('topic');
        $topic_key = 'trends:';

        if(empty($topic)){
            $topic_key .= "_";
        }else{
            $topic_key .= urlencode($topic);
        }

        $topic_cache = Redis::get( $topic_key );
        $output = '';

        function get_data($t){
            $api_url = 'http://api.buzzsumo.com/search/trends.json';

            $form_params = array(
                    'search_type' => 'trending_now',
                    'api_key' => getenv('BUZZSUMO_KEY'),
                    'hours' => '24',
                    //region
                    'count' => '40'
                );

            if(!empty($t)){
                $form_params['topic'] = urlencode($t);
            }

            $client = new Client();
            $res = $client->request('GET', $api_url, [
                'form_params' => $form_params
            ]);
        
            $data_body =  $res->getBody();
            return json_decode($data_body->getContents());
        }

        if( empty( unserialize($topic_cache) ) ){

            $output = get_data($topic);
            Redis::set($topic_key, serialize( $output ));
            Redis::expire($topic_key, 60*20); //set cache for 10 min
        }else{
            $output = unserialize($topic_cache);
        }


        echo json_encode( $output );
        exit;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
