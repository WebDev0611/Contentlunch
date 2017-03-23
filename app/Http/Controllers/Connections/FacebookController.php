<?php

namespace App\Http\Controllers\Connections;

use Illuminate\Http\Request;
use Facebook\Facebook;
use App\Connection;
use Socialite;
use Config;
use Session;
use Auth;
use Exception;

class FacebookController extends BaseConnectionController
{
    protected $fb;

    private function facebookInstance($token)
    {
        return new Facebook([
            'app_id' => Config::get('services.facebook.client_id'),
            'app_secret' => Config::get('services.facebook.client_secret'),
            'default_graph_version' => 'v2.5',
            'default_access_token' => $token,
        ]);
    }

    private function getSelectAccountView()
    {
        $redirectView = Session::get('facebook_view');
        $defaultView = 'settings.connections.facebook.select_account';

        return $redirectView ? $redirectView : $defaultView;
    }

    /*
        -- Need to redirect this, what hapens if they reload the page, it will all crash
        -- redirect with passing connection ID ( incase they are making more then 2 connection to a facebook page )
     */
    public function callback(Request $request)
    {
        try {
            $accessToken = $this->getLongLivedAccessToken();
        } catch (Exception $e) {
            $this->cleanSessionConnection();

            return $this->redirectWithError('The connection request was denied by the user.');
        }

        $settings = [
            'user_token' => (string) $accessToken,
        ];

        $activateConnection = false;
        $connection = $this->saveConnection($settings, 'facebook', $activateConnection);

        $accountOptions = [];
        $accountList = $this->fb->get('/me/accounts');

        foreach ($accountList->getGraphEdge() as $graphNode) {
            $accountOptions[$graphNode['id']] = $graphNode['name'];
        }

        $connection_id = $connection->id;
        $user = Auth::user();

        return view($this->getSelectAccountView(), compact('accountOptions', 'connection_id', 'user'));
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

    private function getLongLivedAccessToken()
    {
        $facebookUser = Socialite::driver('facebook')->user();
        $this->fb = $this->facebookInstance($facebookUser->token);
        $oAuth2Client = $this->fb->getOAuth2Client();

        return $oAuth2Client->getLongLivedAccessToken($facebookUser->token);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function saveAccount(Request $request)
    {
        $connection = Connection::find($request->input('connection_id'));

        if(!$request->has('facebook_account') || empty($request->input('facebook_account'))){
            $connection->delete();
            return redirect()->route($this->redirectRoute());
        }

        $fb = $this->facebookInstance($connection->getSettings()->user_token);

        $response = $fb->get('/'.$request->input('facebook_account').'?fields=access_token');
        $data = $response->getGraphNode();

        $settings = json_decode($connection->settings, true);
        $pageSettings = [
            'page_token' => $data['access_token'],
            'page_id' => $data['id'],
        ];

        $settings = array_merge($settings, $pageSettings);

        $connection->update([
            'settings' => json_encode($settings),
            'active' => true,
            'succesful' => true,
        ]);

        return redirect()
            ->route($this->redirectRoute())
            ->with($this->flashMessage("You've successfully connected to Facebook."));
    }

    protected function redirectRoute()
    {
        return $this->isOnboarding ? 'onboardingConnect' : 'connectionIndex';
    }
}
