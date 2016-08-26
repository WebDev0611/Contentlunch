<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use GuzzleHttp\Client;

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
        $keyword = $request->input('keyword');
        $results = [];
        if(!empty($keyword)){
            $results = $this->get_data( $keyword );
        }
        echo json_encode([$keyword, $results]);
        exit;
        //
    }

    private function get_data($t){
        $api_url = 'http://api3.wordtracker.com/search?';
        $keywords = trim($t);

        $request_string = $api_url . 'app_id=' .getenv('WORDTRACKER_ID') . '&app_key=' . getenv('WORDTRACKER_KEY');
        $request_string .= '&limit=' . 5;
        $request_string .= '&keywords[]=' . urlencode($keywords);
        
      //  exit;
        $client = new Client();
        $res = $client->request('GET', $request_string);
    
        $data_body =  $res->getBody();

        return json_decode($data_body->getContents());
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
