<?php

namespace App\Http\Controllers\Connections;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Redirect;
use Input;
use Auth;
use Exception;

use App\Connection;
use App\TwitterConnection;
use App\Provider;
use Twitter;

class TwitterController extends BaseConnectionController
{
    public function login()
    {
        $signInTwitter = true;
        $forceLogin = false;

        Twitter::reconfig(['token' => '', 'secret' => '']);

        $token = Twitter::getRequestToken(route('twitterCallback'));

        if (isset($token['oauth_token_secret'])) {
            $url = Twitter::getAuthorizeURL($token, $signInTwitter, $forceLogin);

            Session::put('oauth_state', 'start');
            Session::put('oauth_request_token', $token['oauth_token']);
            Session::put('oauth_request_token_secret', $token['oauth_token_secret']);

            return Redirect::to($url);
        }

        return Redirect::route('twitterError');
    }

    public function callback()
    {
        if (Session::has('oauth_request_token'))
        {
            $request_token = [
                'token'  => Session::get('oauth_request_token'),
                'secret' => Session::get('oauth_request_token_secret'),
            ];

            Twitter::reconfig($request_token);

            $oauth_verifier = false;

            if (Input::has('oauth_verifier')) {
                $oauth_verifier = Input::get('oauth_verifier');
            }

            try {
                $token = Twitter::getAccessToken($oauth_verifier);
            } catch (Exception $e) {
                $this->cleanSessionConnection();

                return $this->redirectWithError('The connection request was denied by the user.');
            }

            if (!isset($token['oauth_token_secret'])) {
                $this->cleanSessionConnection();
                return $this->redirectWithError('We could not log you in on Twitter.');
            }

            $credentials = Twitter::getCredentials();

            if (is_object($credentials) && !isset($credentials->error)) {
                $this->saveConnection($token, 'twitter');

                return $this->redirectWithSuccess('Congrats! You\'ve successfully signed in.');
            }

            $this->cleanSessionConnection();
            return $this->redirectWithError('Something went wrong while signing you up.');
        }
    }

    public function userSearch(Request $request)
    {
        $page = $request->input('page');

        $response = Twitter::getUsersSearch([
            'q' => $request->input('query'),
            'count' => 20,
            'page' => $page
        ]);

        return response()->json($response, 200);
    }

    public function error()
    {
        echo('Twitter error');
    }
}
