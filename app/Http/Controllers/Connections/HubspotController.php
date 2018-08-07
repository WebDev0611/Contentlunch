<?php

namespace App\Http\Controllers\Connections;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use oAuth\API\HubspotAuth;
use Connections\API\HubspotAPI;
use Redirect;

class HubspotController extends BaseConnectionController {

    public function __construct (Request $request) {
        $this->auth = new HubspotAuth();
    }

    public function callback (Request $request) {

        $this->cleanSessionConnection();

        if ($error = $request->has('error')) {
            return $this->redirectWithError($this->errorMessage($error));
        }

        $code = $request->input('code');
        $token = $this->auth->codeForToken($code);

        if (collect($token)->has('error')) {
            return $this->redirectWithError('There was an error with your authentication, please try again');
        }

        $tokenArray = (array)$token;
        $connection = $this->saveConnection($tokenArray, 'hubspot');

        return $this->redirectWithSuccess("You've successfully connected to HubSpot.");
    }

    public function saveRefreshedToken ($tokenArray) {
        $this->saveConnection($tokenArray, 'hubspot');

        return $this->redirectWithSuccess("You've successfully connected to HubSpot.");
    }
}
