<?php

namespace App\Http\Controllers;

use Artesaos\LinkedIn\Facades\LinkedIn;
use Connections\API\LinkedInAPI;
use Illuminate\Http\Request;
use Config;
use Auth;
use Redirect;
use Socialite;
use Session;

use App\Http\Requests\Connection\ConnectionRequest;
use App\Connection;
use App\Provider;
use App\Account;

class ConnectionController extends Controller
{
    protected $selectedAccount;

    public function __construct (Request $request) {
        $this->selectedAccount = Account::selectedAccount();
    }

    public function index (Request $request) {
        return response()->json([
            'data' => $this->accountConnections($request)
        ]);
    }

    protected function accountConnections($request)
    {
        return $this->selectedAccount
            ->connections()
            ->active()
            ->selectRaw(
                'connections.*,
                content_types.name as content_type,
                content_types.id as content_type_id')
            ->join('content_types', 'content_types.provider_id', '=', 'connections.provider_id')
            ->when($request->input('content_type'), function($query) use ($request) {
                $query->where('content_types.id', $request->input('content_type'));
            })
            ->get();
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
                $scope = ['manage_pages', 'publish_pages', 'publish_actions', 'pages_show_list'];

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

            case 'mailchimp':
                $url = (new \oAuth\API\MailchimpAuth())->getAuthorizationUrl();

                return Redirect::to($url);

            case 'linkedin':
                $scope = ['r_basicprofile', 'r_emailaddress', 'rw_company_admin', 'w_share'];

                return Socialite::with('linkedin')->scopes($scope)->redirect();
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
        return Connection::create([
            'name' => $request->con_name,
            'provider_id' => Provider::findBySlug($request->con_type)->id,
            'active' => false,
            'succesful' => false,
        ]);
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

        return redirect()->route('connections.index')->with([
            'flash_message' => $flashMessage,
            'flash_message_type' => $flashMessageType,
            'flash_message_important' => true,
        ]);
    }
}
