<?php

namespace App\Http\Controllers\Connections;


use Illuminate\Http\Request;
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

        $accessToken = $this->codeForToken($request->input('code'));

        $tokenArray = [
            'token'        => $user->token,
            'refreshToken' => $user->refreshToken,
            'expiresIn'    => $user->expiresIn
        ];
        $connection = $this->saveConnection($tokenArray, 'google-drive');

        return $this->redirectWithSuccess("You've successfully connected to Google.");
    }

    public function codeForToken($code)
    {
        $auth = new GoogleDriveAuth();
        $auth->client->authenticate($code);

        return $auth->client->getAccessToken();
    }
}