<?php

namespace App\Http\Controllers\Connections;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use oAuth\API\GoogleDriveAuth;

class GoogleDriveController extends BaseConnectionController {

    public function callback (Request $request)
    {
        if ($error = $request->has('error')) {
            $this->cleanSessionConnection();

            return $this->redirectWithError(json_encode($request->input('error')));
        }

        if(!$request->has('code')) {
            return $this->redirectWithError('There was an error with your authentication, please try again');
        }

        $token = $this->codeForToken($request->input('code'));

        if (collect($token)->has('error')) {
            return $this->redirectWithError('There was an error with your authentication, please try again');
        }

        $connection = $this->saveConnection($token, 'google-drive');

        return $this->redirectWithSuccess("You've successfully connected to Google.");
    }

    public function codeForToken($code)
    {
        $auth = new GoogleDriveAuth();
        $auth->client->authenticate($code);

        return $auth->client->getAccessToken();
    }

    public function tokenPostData($code)
    {
        $auth = new GoogleDriveAuth();

        return [
            'client_id' => $auth->client->getClientId(),
            'redirect_uri' =>  $auth->client->getRedirectUri(),
            'client_secret' =>  $auth->client->getClientSecret(),
            'code' => $code,
            'grant_type' => 'authorization_code'
        ];
    }
}