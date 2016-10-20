<?php

namespace App\Http\Controllers;

use App\Http\Requests\Content\ContentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\ContentType;
use App\BuyingStage;
use App\Connection;
use App\Attachment;
use App\Campaign;
use App\Content;
use Socialite;
use App\User;
use App\Tag;
use Storage;
use View;
use Config;
use Auth;
use Redirect;

class ConnectionController extends Controller {

    public function index(Request $request)
    {
        $contentTypeId = $request->get('content_type');

        $data = Connection::selectRaw('connections.*, content_types.name as content_type, content_types.id as content_type_id')
            ->join('content_types', 'content_types.provider_id', '=', 'connections.provider_id');

        if ($contentTypeId) {
            $data = $data->where('content_types.id', $contentTypeId);
        }

        return response()->json([ 'data' => $data->get() ]);
    }

	public function redirectToProvider(Request $request, $provider)
	{
		if ($request->get('redirect_route')) {
			\Session::put('redirect_route', $request->get('redirect_route'));
		}

		switch ($provider) {
			case "facebook":
				$scope = ["publish_pages", "manage_pages"];
				return Socialite::driver($provider)->scopes($scope)->redirect();

			case 'twitter':
				return Redirect::route('twitterLogin');

			case 'wordpress':
				$url = (new \oAuth\API\WordPressAuth)->getAuthorizationUrl();
				return Redirect::to($url);
		}
	}

	public function login($provider)
	{
		// $user = Socialite::driver($provider)->user();
		// dd($user);
		//
		//
		//

		 $fb = new Facebook ([
			  'app_id' => Config::get('services.facebook.client_id'),
			  'app_secret' => Config::get('services.facebook.client_secret'),
			  'default_graph_version' => 'v2.5',
			  'default_access_token' =>  $user->token
		]);
		$oAuth2Client = $fb->getOAuth2Client();
		$accessToken = $oAuth2Client->getLongLivedAccessToken($user->token);
		 $settings = [
		 	'token' => (string) $accessToken
		 ];
		 // save long token
		$conn = new Connection;
		$conn->name = 'Facebook';
		$conn->provider_id = 191;
		$conn->user_id = Auth::id();
		$conn->settings = json_encode($settings);
		$conn->save();
		// get list of accounts to select one
		$response = $fb->get('/me/accounts'); // - get a list of accounts

		// - save selected
		/*

		$settings = [
		 	'token' => $longLivedAccessToken
		 ];

		$conn = new Connection;
		$conn->name = 'Facebook';
		$conn->provider_id = 191;
		$conn->user_id = Auth::id();
		$conn->settings = json_encode($settings);
		$conn->save();*/

		 //dd($user);


		/*// OAuth 2.0 client handler
		$oAuth2Client = $fb->getOAuth2Client();
		$accessToken = $oAuth2Client->getLongLivedAccessToken($user->token);
		$dateToken = $accessToken->getExpiresAt();

		// Exchanges a short-lived access token for a long-lived one
		 $settings = [
		 	'token' => (string) $accessToken
		 ];

		$conn = new Connection;
		$conn->name = 'Facebook';
		$conn->provider_id = 191;
		$conn->user_id = Auth::id();
		$conn->settings = json_encode($settings);
		$conn->save();*/
		$conn = Connection::find(56);
		$settings = json_decode($conn->settings);

		$linkData = [
		'message' => 'Test message front content launch website.',
		//backdated_time
		////published
		];
		//echo $settings->token;die;
		try {
		 // $response = $fb->post('/me/feed?fields=accounts,name,email', $linkData, $accessToken->getValue());
		//$response = $fb->post('/691957114295469/feed', $linkData);
		//$response = $fb->get('/me/accounts'); // - get a list of accounts
		$response = $fb->get('/691957114295469?fields=access_token'); // get access token

		//$response = $fb->post('/691957114295469/feed', $linkData, $accessToken->getValue());

		  } catch(\Facebook\Exceptions\FacebookResponseException $e) {
		  echo 'Graph returned an error: ' . $e->getMessage();
		  exit;
		} catch(\Facebook\Exceptions\FacebookSDKException $e) {
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  exit;
		}

		//$graphNode = $response->getGraphEdge(); /// had to use with get account
		$graphNode = $response->getGraphNode();
		//dd($graphNode['access_token']);

		 $fbpage = new \Facebook\Facebook([
		  'app_id' => Config::get('services.facebook.client_id'),
		  'app_secret' => Config::get('services.facebook.client_secret'),
		  'default_graph_version' => 'v2.5',
		  'default_access_token' =>  $graphNode['access_token']
		]);

		$responsefb = $fbpage->post('/691957114295469/feed', $linkData);

		$graphNode2 = $responsefb->getGraphNode();
		echo 'Posted with id: ' . $graphNode2['id'];
	}

}
