<?php

namespace App\Http\Controllers\Connections;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Facebook\Facebook;
use App\Http\Requests;
use App\Connection;
use Socialite;
use Config;
use App\Provider;
use Auth;

class FacebookController extends Controller
{
    /*
            -- Need to redirect this, what hapens if they reload the page, it will all crash
            -- redirect with passing connection ID ( incase they are making more then 2 connection to a facebook page )
     */
    public function callback(){



            // - get user data
            $user = Socialite::driver('facebook')->user();
            $fb = new Facebook ([
                          'app_id' => Config::get('services.facebook.client_id'),
                          'app_secret' => Config::get('services.facebook.client_secret'),
                          'default_graph_version' => 'v2.5',
                          'default_access_token' =>  $user->token
            ]);
            // - Lets get long lived access token
            $oAuth2Client = $fb->getOAuth2Client();
            $accessToken = $oAuth2Client->getLongLivedAccessToken($user->token);

            $settings = [
                'user_token' => (string) $accessToken
             ];

             // Quick and dirty attachment
             $provider = Provider::findBySlug('facebook');

             // save long user token
            $conn = new Connection;
            $conn->name = 'Facebook - '. $user->name;  // pull this dyanmically
            $conn->provider_id = $provider->id;    // pull this dyanmically
            $conn->user_id = Auth::id();
            $conn->settings = json_encode($settings);
            $conn->save();
       
            $accountOptions = [];
            $accountList = $fb->get('/me/accounts');

            foreach ($accountList->getGraphEdge() as $graphNode) {
                $accountOptions[$graphNode['id'] ] = $graphNode['name'] ;
            }

            $connection_id = $conn->id;

        return view('settings.connections.facebook.select_account', compact('accountOptions', 'connection_id'));




            // - Get App Approval from User to Post on Page / Get User Data
            // - Get User Token
            // - Convert to LongLivedToken
            // - Create new 'Connection' in DB to store Token
            // - Redirect to Dropdown list of Pages user can Admin
            // - Save Selected Page (somewhere)
            // - Get Access token from Page
            // - Save Page Access token (somewhere)
            // - Ready to use
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveAccount(Request $request)
    {
       // dd($request->all());
            $connection = Connection::find($request->input('connection_id'));

            $fb = new Facebook ([
                          'app_id' => Config::get('services.facebook.client_id'),
                          'app_secret' => Config::get('services.facebook.client_secret'),
                          'default_graph_version' => 'v2.5',
                          'default_access_token' =>  $connection->getSettings()->user_token
            ]);


            $response = $fb->get('/'.$request->input('facebook_account').'?fields=access_token');
            $data = $response->getGraphNode();


            $settings = json_decode($connection->settings, true);
            $pageSettings = [
                'page_token' => $data['access_token'],
                'page_id'   => $data['id']
            ];

            $settings =    array_merge($settings, $pageSettings);

            $connection->settings = json_encode($settings);
            $connection->active = 1;
            $connection->save();

            return redirect()->route('connectionIndex');
    }

  
}
