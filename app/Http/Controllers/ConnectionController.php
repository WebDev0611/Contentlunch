<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Auth;
use Redirect;
use Socialite;
use Session;

use App\Http\Requests\Connection\ConnectionRequest;
use App\Connection;
use App\Provider;
use App\Content;
use App\User;
use App\Account;

class ConnectionController extends Controller {
    public function __construct (Request $request) {
        $this->selectedAccount = Account::selectedAccount();
    }

    public function index (Request $request) {
        $contentTypeId = $request->get('content_type');

        $data = $this->selectedAccount
            ->connections()
            ->selectRaw(
                'connections.*,
                content_types.name as content_type,
                content_types.id as content_type_id')
            ->join('content_types', 'content_types.provider_id', '=', 'connections.provider_id');

        if ($contentTypeId) {
            $data = $data->where('content_types.id', $contentTypeId);
        }

        return response()->json(['data' => $data->get()]);
    }

    private function saveParametersToSession (Request $request) {
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

    public function redirectToProvider (Request $request, $provider) {
        $this->saveParametersToSession($request);

        switch ($provider) {
            case 'facebook':
                $scope = ['publish_pages', 'manage_pages'];

                return Socialite::driver($provider)->scopes($scope)->redirect();

            case 'twitter':
                return Redirect::route('twitterLogin');

            case 'hubspot':
                $scope = ['contacts', 'content', 'files'];
                $url = (new \oAuth\API\HubspotAuth())->getAuthorizationUrl($scope);

                return Redirect::to($url);

            case 'wordpress':
                $url = (new \oAuth\API\WordPressAuth())->getAuthorizationUrl();

                return Redirect::to($url);
        }
    }

    public function login ($provider) {
        $fb = new Facebook([
            'app_id' => Config::get('services.facebook.client_id'),
            'app_secret' => Config::get('services.facebook.client_secret'),
            'default_graph_version' => 'v2.5',
            'default_access_token' => $user->token,
        ]);

        $oAuth2Client = $fb->getOAuth2Client();
        $accessToken = $oAuth2Client->getLongLivedAccessToken($user->token);
        $settings = [
            'token' => (string)$accessToken,
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
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
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
        echo 'Posted with id: ' . $graphNode2['id'];
    }

    public function store (ConnectionRequest $request) {
        $connection = $this->createConnection($request);

        $this->selectedAccount->connections()->save($connection);

        $this->clearConnectionsInSession();

        Session::put('connection_data', [
            'meta_data' => $request->input('api'),
            'connection_id' => $connection->id,
        ]);

        // - Lets get out of here
        return redirect()->route('connectionProvider', $request->input('con_type'));
    }

    private function createConnection ($request) {
        $connActive = $request->input('con_active');
        $connType = $request->input('con_type');

        $connection = Connection::create([
            'name' => $request->input('con_name'),
            'provider_id' => Provider::findBySlug($connType)->id,
            'active' => $connActive == 'on' ? 1 : 0
        ]);

        return $connection;
    }

    private function clearConnectionsInSession () {
        Session::forget('connection_data');
        Session::forget('redirect_route');
        Session::forget('facebook_view');
    }

    public function delete (Request $request, Connection $connection) {
        $selectedAccount = Account::selectedAccount();

        if ($connection->belongsToAccount($selectedAccount)) {
            $connection->delete();
            $flashMessage = 'You have successfully disconnected ' . $connection->name . '.';
            $flashMessageType = 'success';
        } else {
            $flashMessage = 'Your account does not have access to the connection ' . $connection->name . '.';
            $flashMessageType = 'danger';
        }

        return redirect()->route('connectionIndex')->with([
            'flash_message' => $flashMessage,
            'flash_message_type' => $flashMessageType,
            'flash_message_important' => true,
        ]);
    }
}
