<?php

namespace App\Http\Controllers;

use App\Account;
use App\Influencer;
use Illuminate\Http\Request;

use App\Http\Requests;

use GuzzleHttp\Client;

class InfluencersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $topic = $request->input('topic');
        $api_url = 'https://api.rightrelevance.com/v2/experts/search?query=' . $topic;
        $api_url .= '&access_token=' . getenv('RIGHTRELEVANCE_TOKEN');

        if (!empty($topic)) {
            $client = new Client();
            $res = $client->request('GET', $api_url );

            $data_body =  $res->getBody();
            echo $data_body;

            exit;
        } else {
            echo json_encode(array('results'=> array()));
            exit;
        }
    }

    public function toggleBookmark(Request $request)
    {
        $twitterId = $request->input('twitter_id_str');
        $selectedAccount = Account::selectedAccount();

        if ($influencer = $selectedAccount->influencers()->where('twitter_id_str', $twitterId)->first()) {
            Account::selectedAccount()->unbookmarkInfluencer($influencer);
            $response = response()->json(['data' => 'Influencer removed.'], 200);
        } else {
            $influencer = Account::selectedAccount()->bookmarkInfluencer($request->all());
            $response = response()->json(['data' => $influencer], 201);
        }

        return $response;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $influencers = Account::selectedAccount()->influencers()->get();

        return response()->json([ 'data' => $influencers ]);
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
