<?php

namespace App\Http\Controllers\Connections;

use Illuminate\Http\Request;
use oAuth\API\DropboxAuth;

class DropboxController extends BaseConnectionController {

    public function __construct (Request $request)
    {
        $this->auth = new DropboxAuth();
    }

    public function callback (Request $request)
    {
        $state = session('dropbox_state', null);

        if ($state !== null && $state === $request->input('state')) {
            // CSRF pass

            if ($error = $request->has('error')) {
                $this->cleanSessionConnection();

                return $this->redirectWithError($this->errorMessage($request, $request->input('error')));
            }

            $code = $request->input('code');
            $token = $this->auth->codeForToken($code);

            if (collect($token)->has('error')) {
                $this->cleanSessionConnection();

                return $this->redirectWithError('There was an error with your authentication, please try again');
            }

            $tokenArray = (array)$token;
            $connection = $this->saveConnection($tokenArray, 'dropbox');

            return $this->redirectWithSuccess("You've successfully connected to Dropbox.");
        }
        else {
            // CSRF fail
            return $this->redirectWithError('There was an error with your authentication, please try again');
        }
    }

    protected function errorMessage (Request $request, $error)
    {
        return $error == 'access_denied' ?
            'You need to authorize ContentLaunch if you want to use the Dropbox connection' :
            $request->input('error_description');
    }
}
