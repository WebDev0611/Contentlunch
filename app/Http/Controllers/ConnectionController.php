<?php

namespace App\Http\Controllers;

use App\Http\Requests\Content\ContentRequest;
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

class ConnectionController extends Controller {

	public function index()
	{
		return response()->json([
			'data' => Connection::with('provider.contentType')->get()
		]);
	}

	public function redirectToProvider($provider) {
		$scope = [];
		// break this out into some dynamic class maybe?
		// -- need to see how this plays out
		switch ($provider)
		{
			case "facebook":
				$scope = ["publish_pages","manage_pages"];
			break;


		}
		return Socialite::driver($provider)->scopes($scope)->redirect();
		// - Facebook
		// -publish_pages - A page access token with publish_pages permission can be used to publish new posts on behalf of that page. Posts will appear in the voice of the page.
		// - publish_actions - A user access token with publish_actions permission can be used to publish new posts on behalf of that person. Posts will appear in the voice of the user.
		// -- End Facebook

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
