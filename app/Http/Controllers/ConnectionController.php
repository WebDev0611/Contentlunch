<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Connection;
use App\Content;
use Socialite;
use App\User;
use Config;
use Auth;
use Redirect;

class ConnectionController extends Controller
{
    public function index(Request $request)
    {
        $contentTypeId = $request->get('content_type');

        $data = Connection::selectRaw('connections.*, content_types.name as content_type, content_types.id as content_type_id')
            ->join('content_types', 'content_types.provider_id', '=', 'connections.provider_id');

        if ($contentTypeId) {
            $data = $data->where('content_types.id', $contentTypeId);
        }

        return response()->json(['data' => $data->get()]);
    }

    private function saveParametersToSession(Request $request)
    {
        $parameters = $request->input();
        $allowedKeys = collect([
            'redirect_route',
            'facebook_view',
            'wordpress_blog_url',
        ]);

        foreach ($parameters as $key => $value) {
            if ($allowedKeys->contains($key)) {
                \Session::put($key, $value);
            }
        }
    }

    public function redirectToProvider(Request $request, $provider)
    {
        $this->saveParametersToSession($request);

        switch ($provider) {
            case 'facebook':
                $scope = ['publish_pages', 'manage_pages'];

                return Socialite::driver($provider)->scopes($scope)->redirect();

            case 'twitter':
                return Redirect::route('twitterLogin');

            case 'wordpress':
                $url = (new \oAuth\API\WordPressAuth())->getAuthorizationUrl();

                return Redirect::to($url);
        }
    }

    public function login($provider)
    {
        $fb = new Facebook([
            'app_id' => Config::get('services.facebook.client_id'),
            'app_secret' => Config::get('services.facebook.client_secret'),
            'default_graph_version' => 'v2.5',
            'default_access_token' => $user->token,
        ]);

        $oAuth2Client = $fb->getOAuth2Client();
        $accessToken = $oAuth2Client->getLongLivedAccessToken($user->token);
        $settings = [
            'token' => (string) $accessToken,
        ];

        $conn = new Connection();
        $conn->name = 'Facebook';
        $conn->provider_id = 191;
        $conn->user_id = Auth::id();
        $conn->settings = json_encode($settings);
        $conn->save();
        // get list of accounts to select one
        $response = $fb->get('/me/accounts'); // - get a list of accounts

        $conn = Connection::find(56);
        $settings = json_decode($conn->settings);

        $linkData = [
	        'message' => 'Test message front content launch website.',
        ];

        try {
	        $response = $fb->get('/691957114295469?fields=access_token'); // get access token
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: '.$e->getMessage();
            exit;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: '.$e->getMessage();
            exit;
        }

        $graphNode = $response->getGraphNode();

        $fbpage = new \Facebook\Facebook([
			'app_id' => Config::get('services.facebook.client_id'),
			'app_secret' => Config::get('services.facebook.client_secret'),
			'default_graph_version' => 'v2.5',
			'default_access_token' => $graphNode['access_token'],
        ]);

        $responsefb = $fbpage->post('/691957114295469/feed', $linkData);

        $graphNode2 = $responsefb->getGraphNode();
        echo 'Posted with id: '.$graphNode2['id'];
    }
}
